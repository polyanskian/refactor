<?php

namespace NW\WebService\References\Operations\Notification\Repository;

use NW\WebService\References\Operations\Notification\Contractor;

class ContractorRepository
{
    public function find($id): ?Contractor
    {
        return Contractor::getById($id);
    }
}
