<?php

namespace NW\WebService\References\Operations\Notification\Service;

use NW\WebService\References\Operations\Notification\EmailMessage\EmailMessage;
use NW\WebService\References\Operations\Notification\Interfaces\MailerInterface;
use NW\WebService\References\Operations\Notification\NotificationEvents;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmployeeEmail(EmailMessage $emailMessage, int $resellerId, NotificationEvents $notificationEvent): bool
    {
        return $this->mailer->send($emailMessage);
    }

    public function sendClientEmail(EmailMessage $emailMessage, int $resellerId, int $id, NotificationEvents $param, int $to): bool
    {
        return $this->mailer->send($emailMessage);
    }
}
