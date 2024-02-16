<?php

namespace NW\WebService\References\Operations\Notification\Service;

class ConfigService
{
    function getEmailsByPermit($resellerId, $event): array
    {
        // fakes the method
        return ['someemeil@example.com', 'someemeil2@example.com'];
    }

    function getResellerEmailFrom(): string
    {
        return 'contractor@example.com';
    }
}
