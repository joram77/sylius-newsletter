<?php


namespace Joram\SyliusNewsletterPlugin\EventListener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class MenuBuilderListener
{
    /**
     * @param MenuBuilderEvent $event
     */
    public function addBackendMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $menu->getChild('catalog')
            ->addChild('joram_admin_newsletter',['route'=>'joram_admin_newsletter_index'])
            ->setLabel('joram.ui.newsletters')
            ->setLabelAttribute('icon', 'envelope');
        $menu = $event->getMenu();
        $menu->getChild('catalog')
        ->addChild('joram_admin_newsletter_subscribers',['route'=>'joram_admin_newsletter_subscribers_index'])
        ->setLabel('joram.ui.newsletter_subscribers')
        ->setLabelAttribute('icon', 'users');

    }
}
