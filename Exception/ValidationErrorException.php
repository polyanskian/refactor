<?php

declare(strict_types=1);

namespace NW\WebService\References\Operations\Notification\Exception;

class ValidationErrorException extends \Exception
{
     /** @var array<string, string>|string[]  */
    private array $errors;

    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<string, string>|string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<string, string>|string[] $errors
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }
}
