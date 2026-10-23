<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Warehouse;

use Linio\SellerCenter\Contract\CollectionInterface;

class Contacts implements CollectionInterface
{
    /**
     * @var Contact[]
     */
    protected $collection = [];

    public function all(): array
    {
        return $this->collection;
    }

    public function add(Contact $contact): void
    {
        $this->collection[] = $contact;
    }
}
