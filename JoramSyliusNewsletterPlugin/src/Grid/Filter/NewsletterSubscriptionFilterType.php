<?php

namespace Joram\SyliusNewsletterPlugin\Grid\Filter;

use Joram\SyliusNewsletterPlugin\Entity\Newsletter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterSubscriptionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'subscription',
            EntityType::class,
            ['class' => Newsletter::class,
            'label' => false ]
        );
    }
}
