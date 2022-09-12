<?php

declare(strict_types=1);

namespace Joram\SyliusNewsletterPlugin\Entity;
use App\Entity\Locale\Locale;
use Joram\SyliusNewsletterPlugin\Entity\Newsletter;
use Sylius\Component\Locale\Model\LocaleInterface;


trait CustomerNewsletterTrait
{
    /**
     * @ORM\ManyToMany(targetEntity=Newsletter::class, inversedBy="subscribers")

     */
    private $subscriptions;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Locale\Locale")
     */
    private ?LocaleInterface $locale;

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

    /**
     * @return LocaleInterface|null
     */
    public function getLocale(): ?LocaleInterface
    {
        return $this->locale;
    }

    /**
     * @param LocaleInterface|null $locale
     */
    public function setLocale(?LocaleInterface $locale): void
    {
        $this->locale = $locale;
    }



}
