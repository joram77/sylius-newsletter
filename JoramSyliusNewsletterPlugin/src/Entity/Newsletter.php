<?php


namespace Joram\SyliusNewsletterPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Joram\SyliusNewsletterPlugin\Repo\NewsletterRepository;
use App\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NewsletterRepository::class)
 * @ORM\Table(name="sylius_newsletter")

 */
class Newsletter implements ResourceInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
    * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected string $subject;

    /**
     * @ORM\Column(type="text", length=255)
     * @Assert\NotBlank
     */
    protected string $content;

    /**
     * @ManyToMany(targetEntity="App\Entity\Customer\Customer", mappedBy="subscriptions")
     */
    protected Collection $subscribers;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
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

}
