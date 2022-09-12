<?php

namespace Joram\SyliusNewsletterPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsletterType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => NewsletterTranslationType::class,
            ]);
    }
    public function getBlockPrefix(): string
    {
        return 'joram_newsletter';
    }
}
