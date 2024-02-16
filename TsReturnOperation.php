<?php

namespace NW\WebService\References\Operations\Notification;

use NW\WebService\References\Operations\Notification\Action\TsReturnAction;
use NW\WebService\References\Operations\Notification\ActionResult\TsReturnActionResult;
use NW\WebService\References\Operations\Notification\Bus\ActionBusInterface;
use NW\WebService\References\Operations\Notification\Exception\InputParamException;
use NW\WebService\References\Operations\Notification\Interfaces\SerializerInterface;

class TsReturnOperation extends ReferencesOperation
{
    private SerializerInterface $serializer;
    private ActionBusInterface $actionBus;

    public function __construct(
        SerializerInterface $serializer,
        ActionBusInterface $actionBus
    ) {
        $this->serializer = $serializer;
        $this->actionBus = $actionBus;
    }

    /**
     * @throws \Exception
     */
    public function doOperation(): array
    {
        try {
            $data = $this->getRequest('data');
        } catch (\Exception $e) {
            throw new InputParamException('Данные в запросе не найдены', 0, $e);
        }

        try {
            /** @var TsReturnAction $action */
            $action = $this->serializer->denormalize(TsReturnAction::class, $data);
        } catch (\Exception $e) {
            throw new InputParamException('Ошибка извлечения параметров из запроса', 0, $e);
        }

        /** @var TsReturnActionResult $result */
        $result = $this->actionBus->dispatch($action);

        return $this->serializer->normalize($result);
    }
}
