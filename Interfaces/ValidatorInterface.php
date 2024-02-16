<?php

namespace NW\WebService\References\Operations\Notification\Interfaces;

interface ValidatorInterface
{
    public function validate($data): array;
}
