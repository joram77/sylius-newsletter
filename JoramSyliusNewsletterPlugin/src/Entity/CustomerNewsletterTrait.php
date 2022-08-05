<?php

declare(strict_types=1);

namespace Joram\SyliusNewsletterPlugin\Entity;
use Joram\SyliusNewsletterPlugin\Entity\Newsletter;


trait CustomerNewsletterTrait
{
    /**
     * @ORM\ManyToMany(targetEntity=Newsletter::class, inversedBy="subscribers")
     */
    private $subscriptions;

    /**
     * @return mixed
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param mixed $subscriptions
     */
    public function setSubscriptions($subscriptions): void
    {
        $this->subscriptions = $subscriptions;
    }

}
