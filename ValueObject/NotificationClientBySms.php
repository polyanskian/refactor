<?php

declare(strict_types=1);

namespace NW\WebService\References\Operations\Notification\ValueObject;

class NotificationClientBySms
{
    public bool $isSent = false;
    public string $message = '';
}
