<?php

namespace NW\WebService\References\Operations\Notification\Repository;

use NW\WebService\References\Operations\Notification\Contractor;
use NW\WebService\References\Operations\Notification\Seller;

class SellerRepository
{
    public function find(int $id): ?Contractor
    {
        return Seller::getById($id);
    }
}
