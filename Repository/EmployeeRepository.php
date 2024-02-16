<?php

namespace NW\WebService\References\Operations\Notification\Repository;

use NW\WebService\References\Operations\Notification\Contractor;
use NW\WebService\References\Operations\Notification\Employee;

class EmployeeRepository
{
    public function find(int $id): ?Contractor
    {
        return Employee::getById($id);
    }
}
