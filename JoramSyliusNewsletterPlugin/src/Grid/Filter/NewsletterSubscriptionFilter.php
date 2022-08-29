<?php

namespace Joram\SyliusNewsletterPlugin\Grid\Filter;


use Joram\SyliusNewsletterPlugin\Repo\NewsletterRepository;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class NewsletterSubscriptionFilter implements FilterInterface
{
    public function apply(DataSourceInterface $dataSource, $name, $data, array $options = []): void
    {
        $dataSource->restrict($dataSource->getExpressionBuilder()->in('subscriptions.id',array($data['subscription'])));
    }
}
