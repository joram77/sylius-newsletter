<?php

declare(strict_types=1);

namespace Joram\SyliusNewsletterPlugin\Form\Extension;

use Sylius\Bundle\CustomerBundle\Form\Type\CustomerProfileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Joram\SyliusNewsletterPlugin\Entity\Newsletter;

final class CustomerProfileTypeExtension extends AbstractTypeExtension
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subscriptions',  EntityType::class, array(
                'class' => Newsletter::class,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'query_builder' => null,
                'by_reference' => true,
            ));
    }

    public static function getExtendedTypes(): iterable
    {
        return [CustomerProfileType::class];
    }

    public function getExtendedType(): string
    {
        return CustomerProfileType::class;
    }

}

