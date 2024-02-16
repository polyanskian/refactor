<?php

namespace NW\WebService\References\Operations\Notification\Repository;

use NW\WebService\References\Operations\Notification\Status;

class StatusRepository
{
    private const DATA = [
        Status::COMPLETED => 'Completed',
        Status::PENDING => 'Pending',
        Status::REJECTED => 'Rejected',
    ];

    public function find(int $id): ?Status
    {
        $statusName = self::DATA[$id] ?? null;

        if (!$statusName) {
            return null;
        }

        return (new Status($id))
            ->setName($statusName);
    }
}
