<?php

namespace NW\WebService\References\Operations\Notification\Factory;

use NW\WebService\References\Operations\Notification\Action\TsReturnAction;
use NW\WebService\References\Operations\Notification\Contractor;
use NW\WebService\References\Operations\Notification\EmailMessage\EmailMessage;
use NW\WebService\References\Operations\Notification\Interfaces\TranslatorInterface;

class EmailFactory
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param array<string, mixed> $templateData
     */
    public function createClientEmail(
        string $emailFrom,
        array $templateData,
        Contractor $clientContractor,
        TsReturnAction $action
    ): EmailMessage {
        $resellerId = $action->getResellerId();

        $clientEmail = new EmailMessage();

        $clientEmail->emailFrom = $emailFrom;
        $clientEmail->emailTo->addEmail($clientContractor->email);
        $clientEmail->subject = $this->translator->trans('complaintClientEmailSubject', $templateData, $resellerId);
        $clientEmail->message = $this->translator->trans('complaintClientEmailBody', $templateData, $resellerId);

        return $clientEmail;
    }

    /**
     * @param string[] $emails
     * @param array<string, mixed> $templateData
     */
    public function createEmployeeEmail(
        string $emailFrom,
        array $emails,
        array $templateData,
        TsReturnAction $action
    ): EmailMessage {
        $resellerId = $action->getResellerId();

        $employeeEmail = new EmailMessage();

        $employeeEmail->emailFrom = $emailFrom;
        $employeeEmail->emailTo->setEmails($emails);
        $employeeEmail->subject = $this->translator->trans('complaintEmployeeEmailSubject', $templateData, $resellerId);
        $employeeEmail->message = $this->translator->trans('complaintEmployeeEmailBody', $templateData, $resellerId);

        return $employeeEmail;
    }
}
