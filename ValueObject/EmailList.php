<?php

namespace NW\WebService\References\Operations\Notification\ValueObject;

class EmailList
{
    private array $emails = [];

    /**
     * @return string[]
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    public function addEmail(string $email): self
    {
        if (!in_array($email, $this->emails, true)) {
            $this->emails[] = $email;
        }

        return $this;
    }

    /**
     * @param string[] $emails
     */
    public function setEmails(array $emails): self
    {
        $this->emails = $emails;

        return $this;
    }
}
