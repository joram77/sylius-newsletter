<?php

declare(strict_types=1);

namespace Joram\Entity\Customer;

use Doctrine\ORM\Mapping as ORM;
use Joram\SyliusNewsletterPlugin\Entity\CustomerNewsletterTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer
{
    use CustomerNewsletterTrait;
}
