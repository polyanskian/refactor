<?php

namespace NW\WebService\References\Operations\Notification\Interfaces;

use NW\WebService\References\Operations\Notification\EmailMessage\EmailMessage;

interface MailerInterface
{
    public function send(EmailMessage $emailMessage): bool;
}
