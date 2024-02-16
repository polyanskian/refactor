<?php

namespace NW\WebService\References\Operations\Notification\Action;

use NW\WebService\References\Operations\Notification\ValueObject\Differences;
use NW\WebService\References\Operations\Notification\ValueObject\NotificationType;

class TsReturnAction
{
    /**
     * @Assert\NotBlank()
     */
    private ?string $date;

    /**
     * @Assert\NotBlank()
     */
    private ?string $agreementNumber;

    /**
     * @Assert\NotBlank()
     */
    private ?string $consumptionNumber;

    /**
     * @Assert\NotNull()
     */
    private ?int $consumptionId;

    /**
     * @Assert\NotBlank()
     */
    private ?string $complaintNumber;

    /**
     * @Assert\NotNull()
     */
    private ?int $complaintId;

    /**
     * @Assert\NotNull()
     */
    private ?int $resellerId;

    /**
     * @Assert\NotNull()
     */
    private ?int $clientId;

    /**
     * @Assert\NotNull()
     */
    private ?int $creatorId;

    /**
     * @Assert\NotNull()
     */
    private ?int $expertId;

    /**
     * @Assert\NotNull(message="Empty notificationType")
     * @Assert\Valid
     */
    private ?NotificationType $notificationType;

    /**
     * @Assert\NotNull()
     */
    private Differences $differences;

    public function __construct(
        ?string $date = null,
        ?string $agreementNumber = null,
        ?string $consumptionNumber = null,
        ?int $consumptionId = null,
        string $complaintNumber = null,
        ?int $complaintId = null,
        ?int $expertId = null,
        ?int $creatorId = null,
        ?int $clientId = null,
        ?int $resellerId = null,
        ?NotificationType $notificationType = null,
        ?Differences $differences = null,
    ) {
        if (!$differences) {
            $differences = new Differences();
        }
    }

    public function getDate(): string
    {
        return (string) $this->date;
    }

    public function getAgreementNumber(): string
    {
        return (string) $this->agreementNumber;
    }

    public function getConsumptionNumber(): string
    {
        return (string) $this->consumptionNumber;
    }

    public function getConsumptionId(): int
    {
        return (int) $this->consumptionId;
    }

    public function getComplaintNumber(): string
    {
        return (string) $this->complaintNumber;
    }

    public function getComplaintId(): int
    {
        return (int) $this->complaintId;
    }

    public function getExpertId(): int
    {
        return (int) $this->expertId;
    }

    public function getCreatorId(): int
    {
        return (int) $this->creatorId;
    }

    public function getClientId(): int
    {
        return (int) $this->clientId;
    }

    public function getResellerId(): int
    {
        return (int) $this->resellerId;
    }

    public function getNotificationType(): NotificationType
    {
        return $this->notificationType;
    }

    public function getDifference(): Differences
    {
        return $this->differences;
    }
}
