<?php

namespace NW\WebService\References\Operations\Notification\EmailMessage;

use NW\WebService\References\Operations\Notification\ValueObject\EmailList;

class EmailMessage
{
    public string $emailFrom = '';

    public EmailList $emailTo;

    public string $subject = '';
    public string $message = '';

    public function __construct()
    {
        $this->emailTo = new EmailList();
    }
}
