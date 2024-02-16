<?php

namespace NW\WebService\References\Operations\Notification\Interfaces;

use NW\WebService\References\Operations\Notification\ValueObject\Notification;

interface NotifierInterface
{
    public function send(Notification $notification): bool;
}
