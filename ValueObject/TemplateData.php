<?php

namespace NW\WebService\References\Operations\Notification\ValueObject;

class TemplateData
{
    /**
     * @Assert\NotNull()
     */
    public ?int $complaintId = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $complaintNumber = null;

    /**
     * @Assert\NotNull()
     */
    public ?int $creatorId = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $creatorName = null;

    /**
     * @Assert\NotNull()
     */
    public ?int $expertId = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $expertName = null;

    /**
     * @Assert\NotNull()
     */
    public ?int $clientId = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $clientName = null;

    /**
     * @Assert\NotNull()
     */
    public ?int $consumptionId = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $consumptionNumber = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $agreementNumber = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $date = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $differences = null;
}
