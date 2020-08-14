<?php

namespace Spark\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;
use Spark\Spark;

class SparkEventDispatcherV3 implements EventDispatcherInterface
{
    use SparkEventDispatcherTrait;
    use AbstractEventDispatcherDecoratorTrait;

    public function dispatch($eventName, Event $event = null): object
    {
        $this->internalDispatch($event, $eventName);
        return $this->dispatcher->dispatch($eventName, $event);
    }
}
