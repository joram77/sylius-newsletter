<?php


namespace Joram\SyliusNewsletterPlugin\Service;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Joram\SyliusNewsletterPlugin\Command\NewsletterSendCommand;
use Joram\SyliusNewsletterPlugin\Repo\NewsletterRepository;
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
    private NewsletterRepository $newsletterRepository;
    private MailerInterface $mailer;
    private EntityManagerInterface $entityManager;
    private ContainerInterface $container;
    private MessageLoggerListener $messageLoggerListener;
    private Environment $templating;


    public function __construct(NewsletterRepository $newsletterRepository, MailerInterface $mailer,
                                EntityManagerInterface $em, ContainerInterface $container,
                                Environment $templating
    )
    {
        $this->newsletterRepository = $newsletterRepository;
        $this->mailer = $mailer;
        $this->entityManager = $em;
        $this->container = $container;
        $this->messageLoggerListener = $container->get('joram.mailer.message_logger_listener');
        $this->templating = $templating;
    }

    public function send($newsletterId, OutputInterface $output): void
    {
        $newsletter = $this->newsletterRepository->find($newsletterId);
        if ($newsletter == null) {
            echo "Newsletter with id {$newsletterId} does not exist\n";
        } else {
            $numSent = 0;
            $numSubscribers = $newsletter->getSubscribers()->count();
            echo "Sending newsletter {$newsletter->getId()} {$newsletter->getSubject()}...\n";
            $i = 0;
            $errors = '';
            try {
                $template = $this->templating->createTemplate(nl2br($newsletter->getContent()));
            } catch (LoaderError|SyntaxError $e) {
                echo "Error parsing newsletter " . $e->getMessage();
                return;
            }
            foreach ($newsletter->getSubscribers() as $subscriber) {
                $parsedContent = $template->render(array('first_name' => $subscriber->getFirstname(),
                    'newsletter_name' => $newsletter->getSubject()));
                if ($i !== 0) {
                    NewsletterSendCommand::clearLine($output);
                }
                $i++;
                echo 'Sent to subscriber ' . $i . ' of ' . $numSubscribers . "\n";
                $mail = (new Email())->from('no-reply@netenders.com');
                $mail->to($subscriber->getUser()->getEmail())
                    ->subject($newsletter->getSubject())
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
