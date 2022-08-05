<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Joram\SyliusNewsletterPlugin\Repo;

use Joram\SyliusNewsletterPlugin\Entity\Newsletter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class NewsletterRepository extends EntityRepository
{
    public function __construct(EntityManager $registry)
    {
        parent::__construct($registry, new ClassMetadata(Newsletter::class));
    }
}
