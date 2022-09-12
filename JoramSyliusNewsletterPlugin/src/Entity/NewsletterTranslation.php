<?php

namespace Joram\SyliusNewsletterPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\ResourceInterface;
/**
 * @ORM\Entity(repositoryClass=NewsletterTranslationRepository::class)
 * @ORM\Table(name="sylius_newsletter_translation")
 */
class NewsletterTranslation extends AbstractTranslation implements ResourceInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected ?string $subject;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    protected ?string $content;

    public function getId()
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getContent():?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

}
