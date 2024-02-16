<?php

namespace NW\WebService\References\Operations\Notification\ActionResult;

use NW\WebService\References\Operations\Notification\ValueObject\NotificationClientBySms;

class TsReturnActionResult
{
    public bool $notificationEmployeeByEmail = false;
    public bool $notificationClientByEmail = false;
    public NotificationClientBySms $notificationClientBySms;

    public function __construct()
    {
        $this->notificationClientBySms = new NotificationClientBySms();
    }
}
