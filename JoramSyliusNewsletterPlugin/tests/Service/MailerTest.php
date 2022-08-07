<?php

namespace Joram\SyliusNewsletterPlugin\Service;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

class MailerTest implements MailerInterface
{
    const MAX_MESSAGES_TO_KEEP = 1000;
    private array $receivedMessages = array();

    /**
     * @return array
     */
    public function getReceivedMessages(): array
    {
        return $this->receivedMessages;
    }

    public function send(RawMessage $message, Envelope $envelope = null): void
    {
        $this->receivedMessages[] = $message;
        if (count($this->receivedMessages) >= self::MAX_MESSAGES_TO_KEEP) {
            array_pop($this->receivedMessages);
        }
    }

}
