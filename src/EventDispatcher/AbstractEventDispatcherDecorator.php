<?php

namespace Spark\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractEventDispatcherDecorator implements EventDispatcherInterface
{
    use AbstractEventDispatcherDecoratorTrait;

    /**
     * {@inheritdoc}
     */
    public function dispatch(object $event, ?string $eventName = NULL): object
    {
        return $this->dispatcher->dispatch($event, $eventName);
    }
}
