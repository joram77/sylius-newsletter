<?php

namespace Joram\SyliusNewsletterPlugin\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Joram\SyliusNewsletterPlugin\Entity\Newsletter;
use Joram\SyliusNewsletterPlugin\Repo\NewsletterRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Mailer\EventListener\MessageLoggerListener;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Sylius\Component\Core\Model\ShopUser;
use Sylius\Component\Core\Model\Customer;

class NewsletterSendServiceTest extends TestCase
{
    public function testSend()
    {
        $newsletter = new Newsletter();
        $newsletter->setContent('Hi {{first_name}} You are receiving {{newsletter_name}}');
        $newsletter->setSubject('the newsletter you want to read');
        $customers = new ArrayCollection();
        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer();
            $shopUser = new ShopUser();
            $shopUser->setCustomer($customer);
            $customer->setUser($shopUser);
            $shopUser->setEmail('test' . $i . '@example.com');
            $customer->setFirstName('Testdummy ' . ($i + 1));
            $customers->add($customer);
        }
        $newsletter->setSubscribers($customers);
        $newsletterRepository = $this->createMock(NewsletterRepository::class);
        $newsletterRepository->expects($this->any())
            ->method('find')
            ->willReturn($newsletter);
        $container = $this->createMock(Container::class);
        $messageLoggerListener = $this->createMock(MessageLoggerListener::class);
        $messageLoggerListener->expects($this->any())->method('reset');
        $container->expects($this->any())
            ->method('get')
            ->willReturn($messageLoggerListener);
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
            'Hi Testdummy 10 You are receiving the newsletter you want to read',
            $lastMessage->getHtmlBody());
    }
}

