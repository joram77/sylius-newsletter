<?php


namespace Joram\SyliusNewsletterPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Joram\SyliusNewsletterPlugin\Repo\NewsletterRepository;
use App\Entity\Customer;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * @ORM\Entity(repositoryClass=NewsletterRepository::class)
 * @ORM\Table(name="sylius_newsletter")
 */
class Newsletter implements ResourceInterface, TranslatableInterface
{
    use TranslatableTrait {
        TranslatableTrait::__construct as private initializeTranslationsCollection;
    }

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @ManyToMany(targetEntity="App\Entity\Customer\Customer", mappedBy="subscriptions")
     */
    protected Collection $subscribers;

    public function getSubject(): string
    {
        return $this->getTranslation()->getSubject();
    }

    public function setSubject($subject)
    {
        $this->getTranslation()->setSubject($subject);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getTranslation()->getContent();
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->getTranslation()->setContent($content);
    }


    public function getId()
    {
        return $this->id;
    }


    /**
     * @return Collection
     */
    public function getSubscribers(): Collection
    {
        return $this->subscribers;
    }

    /**
     * @param Collection $subscribers
     */
    public function setSubscribers(Collection $subscribers): void
    {
        $this->subscribers = $subscribers;
    }

    public function __toString()
    {
        return $this->getSubject();
    }


    protected function createTranslation(): TranslationInterface
    {
        // TODO: Implement createTranslation() method.
        return new NewsletterTranslation();
    }
}
