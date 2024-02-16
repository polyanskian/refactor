<?php

namespace NW\WebService\References\Operations\Notification\Interfaces;

interface TranslatorInterface
{
    /**
     * @param array<string, mixed> $params
     */
    public function trans(string $text, array $params, int $domain): string;
}
