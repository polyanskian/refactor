<?php

namespace NW\WebService\References\Operations\Notification\ValueObject;

class NotificationType
{
    public const TYPE_NEW = 1;
    public const TYPE_CHANGE = 2;

    /**
     * @Assert\Choises({self::TYPE_NEW, self::TYPE_CHANGE})
     */
    private int $type;

    public function __construct(int $type)
    {
        $this->type = $type;
    }

    public function isTypeNew(): bool
    {
        return $this->type === self::TYPE_NEW;
    }

    public function isTypeChange(): bool
    {
        return $this->type === self::TYPE_CHANGE;
    }
}
