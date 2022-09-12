<?php


namespace Joram\SyliusNewsletterPlugin\Service;


use App\Entity\Customer\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Joram\SyliusNewsletterPlugin\Command\NewsletterSendCommand;
use Joram\SyliusNewsletterPlugin\Entity\NewsletterTranslation;
use Joram\SyliusNewsletterPlugin\Repo\NewsletterRepository;
use Joram\SyliusNewsletterPlugin\Repo\NewsletterTranslationRepository;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\EventListener\MessageLoggerListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

class NewsletterSendService
{

    public const FREE_SERVICES_MEMORY_NUM_RECORDS = 500;
    private MessageLoggerListener $messageLoggerListener;

    public function __construct(private NewsletterRepository   $newsletterRepository,
                                private MailerInterface        $mailer,
                                private EntityManagerInterface $em,
                                private ContainerInterface     $container,
                                private Environment            $templating
    )
    {
        $this->messageLoggerListener = $container->get('joram.mailer.message_logger_listener');
    }

    public function send($newsletterId, OutputInterface $output): void
    {
        $newsletter = $this->newsletterRepository->find($newsletterId);
        if ($newsletter == null) {
            echo "Newsletter with id {$newsletterId} does not exist\n";
        } else {
            echo "STRAT";
            $numSent = 0;
            $numSubscribers = $newsletter->getSubscribers()->count();
            echo "Sending newsletter {$newsletter->getId()} {$newsletter->getSubject()}...\n";
            $i = 0;
            $errors = '';
            /* @var Customer */
            foreach ($newsletter->getSubscribers() as $subscriber) {
                $i++;
                echo 'Send to subscriber ' . $i . ' of ' . $numSubscribers . "\n";
                $locale = $subscriber->getLocale()?->getCode() ?? $this->container->get('sylius.context.locale')->getLocaleCode();
                $translation = $newsletter->getTranslation($locale);
                if ($translation->getContent() === null || $translation->getSubject() === null) {
                    echo "Translation has an empty subject or content. Skipping.\n";
                    $errors .= "Error sending to 1 recipient because translation has empty fields.\n";
                    continue;
                }
                try {
                    $template = $this->templating->createTemplate(nl2br($translation->getContent()));
                } catch (LoaderError|SyntaxError $e) {
                    echo "Error parsing newsletter content. Skipping." . $e->getMessage() . "\n";
                    $errors .= "Error sending to 1 recipient " . $e->getMessage() . "\n";
                    continue;
                }

                $parsedContent = $template->render(array('first_name' => $subscriber->getFirstname(),
                    'newsletter_name' => $translation->getSubject()));
                $mail = new Email();
                $mail->to($subscriber->getUser()->getEmail())
                    ->subject($translation->getSubject())
                    ->html($parsedContent);
                try {
                    $this->mailer->send($mail);
                    $numSent += 1;
                } catch (TransportExceptionInterface $e) {
                    $errors .= "Error sending to 1 recipient " . $e->getMessage() . "\n";
                }
                if ($i % self::FREE_SERVICES_MEMORY_NUM_RECORDS == 0) {
                    $this->entityManager->clear();
                    $this->messageLoggerListener->reset();
                }

            }
            echo 'Done sending ' . $numSent . " messages.\n";
            if ($errors != '') echo "Error sending messages: $errors\n";
        }
    }
}
