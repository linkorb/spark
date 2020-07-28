<?php

namespace Spark\LegacyEventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;
use Spark\Spark;
use Spark\EventDispatcher\SparkEventDispatcherTrait;

class LegacySparkEventDispatcher extends LegacyAbstractEventDispatcherDecorator
{
    use SparkEventDispatcherTrait;

    public function dispatch($eventName, Event $event =  null): object
    {
        if (is_a($event, LegacySparkEvent::class)) {
            $eventName = $event->getName();
        }
        $this->events[] = [
            $event,
            $eventName,
            microtime(true)
        ];

        return parent::dispatch($eventName, $event);
    }
}
