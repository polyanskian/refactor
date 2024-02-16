<?php

namespace NW\WebService\References\Operations\Notification\ValueObject;

use NW\WebService\References\Operations\Notification\NotificationEvents;

class Notification
{
    public int $resellerId = 0;
    public int $clientId = 0;
    public ?NotificationEvents $notificationEvent = null;
    public int $differencesTo;
    public array $templateData = [];
    public string $error = '';

    public function __construct(int $differencesTo)
    {
        $this->differencesTo = $differencesTo;
    }
}
