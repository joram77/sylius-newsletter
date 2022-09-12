<?php

namespace Joram\SyliusNewsletterPlugin\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Joram\SyliusNewsletterPlugin\Entity\Newsletter;
use Joram\SyliusNewsletterPlugin\Entity\NewsletterTranslation;
use Joram\SyliusNewsletterPlugin\Repo\NewsletterRepository;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Mailer\EventListener\MessageLoggerListener;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Sylius\Component\Core\Model\ShopUser;
use Sylius\Component\Locale\Model\Locale;
use Joram\SyliusNewsletterPlugin\Entity\Customer;

class NewsletterSendServiceTest extends TestCase
{
    public function testSend()
    {
        $newsletter = new Newsletter();
        $newsletterTranslation  = new NewsletterTranslation();
        $newsletterTranslation->setContent('Hi {{first_name}} You are receiving {{newsletter_name}}');
        $newsletterTranslation->setSubject('the newsletter you want to read');
        $newsletterTranslation->setLocale('en_US');
        $newsletter->addTranslation($newsletterTranslation);
        $newsletterTranslation  = new NewsletterTranslation();
        $newsletterTranslation->setContent('Hola {{first_name}} Recibes {{newsletter_name}}');
        $newsletterTranslation->setSubject('el boletín que quieres leer');
        $newsletterTranslation->setLocale('es_ES');

        $newsletter->addTranslation($newsletterTranslation);
        $newsletter->setCurrentLocale('en_US');
        $customers = new ArrayCollection();
        for ($i = 0; $i < 9; $i++) {
            $customers->add($this->createDummyCustomer($i));
        }
        $customers->add($this->createDummyCustomer(9, 'es_ES'));
        $newsletter->setSubscribers($customers);
        $newsletterRepository = $this->createMock(NewsletterRepository::class);
        $newsletterRepository->expects($this->any())
            ->method('find')
            ->willReturn($newsletter);
        $container = $this->createMock(Container::class);
        $messageLoggerListener = $this->createMock(MessageLoggerListener::class);
        $messageLoggerListener->expects($this->any())->method('reset');
        $localeDefault = $this->createMock(LocaleContextInterface::class);
        $localeDefault->expects($this->any())
            ->method('getLocaleCode')->willReturn('en_US');
        $container->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($messageLoggerListener,
                $localeDefault));

        $loader = new ArrayLoader();
        $templating = new Environment($loader);
        $mailer = new MailerTest();
        $newsletterSendService = new NewsletterSendService($newsletterRepository,
            $mailer,
            $this->createMock(EntityManager::class),
            $container,
            $templating
        );
        $newsletterSendService->send(1, new StreamOutput(STDOUT));
        $messages = $mailer->getReceivedMessages();

        /* @var EMail */
        $firstMessage = $messages[0];
        /* @var EMail */
        $lastMessage = $messages[9];
        $this->assertSame(
            'Hi Testdummy 1 You are receiving the newsletter you want to read',
            $firstMessage->getHtmlBody());
        $this->assertSame(
            'Hola Testdummy 10 Recibes el boletín que quieres leer',
            $lastMessage->getHtmlBody());
    }

    /**
     * @param int $i
     * @param string $localeCode
     * @return Customer
     */
    public static function createDummyCustomer(int $i, string $localeCode = 'en_US'): Customer
    {
        $customer = new Customer();
        $shopUser = new ShopUser();
        $shopUser->setCustomer($customer);
        $customer->setUser($shopUser);
        $shopUser->setEmail('test' . $i . '@example.com');
        $customer->setFirstName('Testdummy ' . ($i + 1));
        $locale = new Locale();
        $locale->setCode($localeCode);
        $customer->setLocale($locale);
        return $customer;
    }
}

