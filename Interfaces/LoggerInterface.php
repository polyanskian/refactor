<?php

namespace NW\WebService\References\Operations\Notification\Interfaces;

interface LoggerInterface
{
    public function log(string $message, ?\Throwable $throwable = null);
}
