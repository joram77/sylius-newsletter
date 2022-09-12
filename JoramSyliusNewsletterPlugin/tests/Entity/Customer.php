<?php

declare(strict_types=1);

namespace Joram\SyliusNewsletterPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer
{
    use CustomerNewsletterTrait;
}
