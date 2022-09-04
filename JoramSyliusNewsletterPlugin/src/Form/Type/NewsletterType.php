<?php

namespace Joram\SyliusNewsletterPlugin\Form\Type;

use App\Entity\Customer\Customer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class NewsletterType extends AbstractType
{
    private $defaultContent;

    public function __construct()
    {
        $this->defaultContent = "Hi {{first_name}},\nYou are receiving this email because you have subscribed to {{newsletter_name}}.\nNetenders.";

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subject', TextType::class);
        $defaultContent = $this->defaultContent;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($defaultContent) {
            $newsletter = $event->getData();
            $form = $event->getForm();
            if (!$newsletter || null === $newsletter->getId()) {
                $form->add(
                    'content',
                    TextareaType::class,
                    [
                        'data' => $defaultContent
                    ]
                );
            } else $form->add('content', TextareaType::class);
        });

    }
}
