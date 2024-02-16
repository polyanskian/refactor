<?php

namespace NW\WebService\References\Operations\Notification\Bus;

use NW\WebService\References\Operations\Notification\Action\TsReturnAction;

interface ActionBusInterface
{
    public function dispatch(TsReturnAction $action): object;
}
