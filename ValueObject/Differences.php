<?php

namespace NW\WebService\References\Operations\Notification\ValueObject;

class Differences
{
    private ?int $from;
    private ?int $to;

    public function isNotSet(): bool
    {
        return $this->from === null && $this->to === null;
    }

    public function getFrom(): int
    {
        return (int) $this->from;
    }

    public function setFrom(?int $from): Differences
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): int
    {
        return (int) $this->to;
    }

    public function setTo(?int $to): Differences
    {
        $this->to = $to;

        return $this;
    }
}
