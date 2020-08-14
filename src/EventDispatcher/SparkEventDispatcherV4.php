<?php

namespace Spark\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Spark\Spark;

class SparkEventDispatcherV4 implements EventDispatcherInterface
{
    use SparkEventDispatcherTrait;
    use AbstractEventDispatcherDecoratorTrait;

    public function dispatch($event, ?string $eventName = NULL): object
    {
        $this->internalDispatch($event, $eventName);
        return $this->dispatcher->dispatch($event, $eventName);
    }
}
