<?php

namespace Spark\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Spark\Spark;

class SparkEventDispatcher extends AbstractEventDispatcherDecorator
{
    use SparkEventDispatcherTrait;

    public function dispatch(object $event, ?string $eventName = NULL): object
    {
        if (is_a($event, SparkEvent::class)) {
            $eventName = $event->getName();
        }
        $this->events[] = [
            $event,
            $eventName,
            microtime(true)
        ];

        return parent::dispatch($event, $eventName);
    }
}
