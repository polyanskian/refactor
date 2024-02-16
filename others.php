<?php

namespace NW\WebService\References\Operations\Notification;

/**
 * @property Seller $Seller
 */
class Contractor
{
    const TYPE_CUSTOMER = 0;

    public int $id;
    public $type = -1;
    public $name = '';
    public string $email = '';
    public $mobile = null;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function getById(int $resellerId): self
    {
        return new self($resellerId); // fakes the getById method
    }

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->id;
    }

    public function isTypeCustomer(): bool
    {
        return $this->type === self::TYPE_CUSTOMER;
    }
}

class Seller extends Contractor
{
}

class Employee extends Contractor
{
}

class Status
{
    public const COMPLETED = 0;
    public const PENDING = 1;
    public const REJECTED = 2;

    public int $id;
    public string $name = '';

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function getName(int $id): string
    {
        $a = [
            0 => 'Completed',
            1 => 'Pending',
            2 => 'Rejected',
        ];

        return $a[$id];
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}

abstract class ReferencesOperation
{
    abstract public function doOperation(): array;

    public function getRequest($pName)
    {
        return $_REQUEST[$pName];
    }
}

function getResellerEmailFrom()
{
    return 'contractor@example.com';
}

function getEmailsByPermit($resellerId, $event)
{
    // fakes the method
    return ['someemeil@example.com', 'someemeil2@example.com'];
}

class NotificationEvents
{
    const CHANGE_RETURN_STATUS = 'changeReturnStatus';
    const NEW_RETURN_STATUS = 'newReturnStatus';

    private string $status;

    public function __construct(string $status)
    {
        if ($this->isValidStatus($status)) {
            throw new \RuntimeException(sprintf('Error support status: %s', $status));
        }

        $this->status = $status;
    }

    public static function createNewReturnStatus(): self
    {
        return new self(self::NEW_RETURN_STATUS);
    }

    public static function createChangeReturnStatus(): self
    {
        return new self(self::CHANGE_RETURN_STATUS);
    }

    public function isStatusChangeReturn(): bool
    {
        return $this->status === self::CHANGE_RETURN_STATUS;
    }

    public function isStatusNewReturn(): bool
    {
        return $this->status === self::NEW_RETURN_STATUS;
    }

    private function isValidStatus(string $status): bool
    {
        $statuses =  [
            self::CHANGE_RETURN_STATUS,
            self::NEW_RETURN_STATUS
        ];

        return in_array($status, $statuses, true);
    }
}
