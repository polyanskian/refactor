<?php

namespace NW\WebService\References\Operations\Notification\ActionHandler;

use NW\WebService\References\Operations\Notification\Action\TsReturnAction;
use NW\WebService\References\Operations\Notification\ActionResult\TsReturnActionResult;
use NW\WebService\References\Operations\Notification\Contractor;
use NW\WebService\References\Operations\Notification\Exception\LogicException;
use NW\WebService\References\Operations\Notification\Exception\TemplateErrorException;
use NW\WebService\References\Operations\Notification\Exception\ValidationErrorException;
use NW\WebService\References\Operations\Notification\Factory\EmailFactory;
use NW\WebService\References\Operations\Notification\Interfaces\LoggerInterface;
use NW\WebService\References\Operations\Notification\Interfaces\NotifierInterface;
use NW\WebService\References\Operations\Notification\Interfaces\SerializerInterface;
use NW\WebService\References\Operations\Notification\Interfaces\TranslatorInterface;
use NW\WebService\References\Operations\Notification\Interfaces\ValidatorInterface;
use NW\WebService\References\Operations\Notification\NotificationEvents;
use NW\WebService\References\Operations\Notification\Repository\ContractorRepository;
use NW\WebService\References\Operations\Notification\Repository\EmployeeRepository;
use NW\WebService\References\Operations\Notification\Repository\SellerRepository;
use NW\WebService\References\Operations\Notification\Repository\StatusRepository;
use NW\WebService\References\Operations\Notification\Service\ConfigService;
use NW\WebService\References\Operations\Notification\Service\EmailService;
use NW\WebService\References\Operations\Notification\ValueObject\Notification;
use NW\WebService\References\Operations\Notification\ValueObject\TemplateData;

class TsReturnActionHandler
{
    private TranslatorInterface $translator;
    private NotifierInterface $notifier;
    private SellerRepository $sellerRepository;
    private ContractorRepository $contractorRepository;
    private EmployeeRepository $employeeRepository;
    private StatusRepository $statusRepository;
    private EmailService $emailService;
    private ValidatorInterface $validator;
    private ConfigService $configService;
    private SerializerInterface $normalizer;
    private LoggerInterface $logger;
    private EmailFactory $emailFactory;

    public function __construct(
        TranslatorInterface $translator,
        NotifierInterface $notifier,

        SellerRepository $sellerRepository,
        ContractorRepository $contractorRepository,
        EmployeeRepository $employeeRepository,
        StatusRepository $statusRepository,

        EmailService $emailService,
        ValidatorInterface $validator,
        ConfigService $configService,
        SerializerInterface $normalizer,
        LoggerInterface $logger,
        EmailFactory $emailFactory,
    ) {
        $this->translator = $translator;
        $this->notifier = $notifier;
        $this->sellerRepository = $sellerRepository;
        $this->contractorRepository = $contractorRepository;
        $this->employeeRepository = $employeeRepository;
        $this->statusRepository = $statusRepository;
        $this->emailService = $emailService;
        $this->validator = $validator;
        $this->configService = $configService;
        $this->normalizer = $normalizer;
        $this->logger = $logger;
        $this->emailFactory = $emailFactory;
    }

    public function __invoke(TsReturnAction $action): TsReturnActionResult
    {
        // action уже приходит валидный, но если нужно надежнее, тогда еще разок
        $errors = $this->validator->validate($action);
        if ($errors) {
            throw (new ValidationErrorException('The request contains errors'))->setErrors($errors);
        }

        $result = new TsReturnActionResult();

        if (!$action->getResellerId()) {
            $result->notificationClientBySms->message = 'Empty resellerId';
            return $result;
        }

        $resellerSeller = $this->sellerRepository->find($action->getResellerId());
        if (!$resellerSeller) {
            throw new LogicException('Seller not found!', 400);
        }

        $clientContractor = $this->contractorRepository->find($action->getClientId());
        if (!$clientContractor) {
            throw new LogicException('Client not found!', 400);
        } elseif (!$clientContractor->isTypeCustomer()) {
            throw new LogicException('Client not found!', 400);
        }

        $creatorEmployee = $this->employeeRepository->find($action->getCreatorId());
        if (!$creatorEmployee) {
            throw new LogicException('Creator not found!', 400);
        }

        $expertEmployee = $this->employeeRepository->find($action->getExpertId());
        if ($expertEmployee === null) {
            throw new LogicException('Expert not found!', 400);
        }

        $templateData = $this->makeTemplateData($action, $creatorEmployee, $expertEmployee, $clientContractor);

        $emailFrom = $this->configService->getResellerEmailFrom();

        // Отправить письмо Employee
        if ($emailFrom) {
            // Получаем email сотрудников из настроек
            $emails = $this->configService->getEmailsByPermit($action->getResellerId(), 'tsGoodsReturn');

            if ($emails) {
                $result->notificationEmployeeByEmail = $this->sendEmailToEmployee($emailFrom, $emails, $templateData, $action);
            }
        }

        // Шлём клиентское уведомление, только если произошла смена статуса
        $isChangeStatus = $action->getNotificationType()->isTypeChange() && $action->getDifference()->getTo();
        if ($isChangeStatus) {
            // Отправить письмо Client
            if ($emailFrom && $clientContractor->email) {
                $result->notificationClientByEmail = $this->sendEmailToClient($emailFrom, $templateData, $action, $clientContractor);
            }

            if ($clientContractor->mobile) {
                // TODO: непонятно откуда берется - похоже надо выпилить
                $error = '';

                $notification = $this->createNotification($error, $action, $clientContractor);
                $result->notificationClientBySms->isSent = $this->sendNotificationToClient($notification);

                if ($error) {
                    $result->notificationClientBySms->message = $error;
                }
            }
        }

        return $result;
    }

    public function sendNotificationToClient(Notification $notification): bool
    {
        try {
            return $this->notifier->send($notification);
        } catch (\Exception $e) {
            $this->logger->log('Error send notification notificationClientBySms', $e);
        }

        return false;
    }

    private function makeDifference(TsReturnAction $action): string
    {
        $difference = '';

        if ($action->getNotificationType()->isTypeNew()) {
            $difference = $this->translator->trans('NewPositionAdded', [], $action->getResellerId());
        } elseif ($action->getNotificationType()->isTypeChange() && $action->getDifference()->isNotSet()) {
            $statusFrom = $this->statusRepository->find($action->getDifference()->getFrom());
            if (!$statusFrom) {
                throw new LogicException('Minimum status not found'); // Минимальный статус не найден
            }

            $statusTo = $this->statusRepository->find($action->getDifference()->getTo());
            if (!$statusTo) {
                throw new LogicException('Maximum status not found'); // Максимальный статус не найден
            }

            $difference = $this->translator->trans('PositionStatusHasChanged', [
                'FROM' => $statusFrom->name,
                'TO' => $statusTo->name,
            ], $action->getResellerId());
        }

        return $difference;
    }

    public function makeTemplateData(
        TsReturnAction $action,
        Contractor $creatorEmployee,
        Contractor $expertEmployee,
        Contractor $clientContractor
    ): array {
        $template = new TemplateData();

        $difference = $this->makeDifference($action);

        $template->complaintId = $action->getComplaintId();
        $template->complaintNumber = $action->getComplaintNumber();
        $template->creatorId = $action->getCreatorId();
        $template->creatorName = $creatorEmployee->getFullName();
        $template->expertId = $action->getExpertId();
        $template->expertName = $expertEmployee->getFullName();
        $template->clientId = $action->getClientId();
        $template->clientName = $clientContractor->getFullName();
        $template->consumptionId = $action->getConsumptionId();
        $template->consumptionNumber = $action->getConsumptionNumber();
        $template->agreementNumber = $action->getAgreementNumber();
        $template->date = $action->getDate();
        $template->differences = $difference;

        // Если хоть одна переменная для шаблона не задана, то не отправляем уведомления
        $errors = $this->validator->validate($template);
        if (!$errors) {
            $errorKey = array_key_first($errors);
            throw new TemplateErrorException(sprintf('Template Data (%s) is empty!', $errorKey), 500);
        }

        return $this->normalizer->normalize($template, [
            'keyCamelCase' => true,
            'keyUpperCase' => true,
        ]);
    }

    /**
     * @param string[] $emails
     * @param array<string, mixed> $templateData
     */
    public function sendEmailToEmployee(
        string $emailFrom,
        array $emails,
        array $templateData,
        TsReturnAction $action
    ): bool {
        $employeeEmail = $this->emailFactory->createEmployeeEmail($emailFrom, $emails, $templateData, $action);

        try {
            return $this->emailService->sendEmployeeEmail(
                $employeeEmail,
                $action->getResellerId(),
                NotificationEvents::createChangeReturnStatus()
            );
        } catch (\Exception $e) {
            $this->logger->log('Error send email notificationEmployeeByEmail', $e);
        }

        return false;
    }

    /**
     * @param array<string, mixed> $templateData
     */
    public function sendEmailToClient(
        string $emailFrom,
        array $templateData,
        TsReturnAction $action,
        Contractor $clientContractor
    ): bool {
        $clientEmail = $this->emailFactory->createClientEmail($emailFrom, $templateData, $clientContractor, $action);

        try {
            return $this->emailService->sendClientEmail(
                $clientEmail,
                $action->getResellerId(),
                $clientContractor->id,
                NotificationEvents::createChangeReturnStatus(),
                $action->getDifference()->getTo()
            );
        } catch (\Exception $e) {
            $this->logger->log('Error send email notificationClientByEmail', $e);
        }

        return false;
    }

    public function createNotification(string $error, TsReturnAction $action, Contractor $clientContractor): Notification
    {
        $notification = new Notification($action->getDifference()->getTo());

        $notification->resellerId = $action->getResellerId();
        $notification->clientId = $clientContractor->id;
        $notification->notificationEvent = NotificationEvents::createChangeReturnStatus();
        $notification->error = $error;

        return $notification;
    }
}
