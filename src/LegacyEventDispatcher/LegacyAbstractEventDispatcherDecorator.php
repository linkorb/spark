<?php

namespace Spark\LegacyEventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Spark\EventDispatcher\AbstractEventDispatcherDecoratorTrait;

/**
 * This class can be used to wrap a legacy dispatcher (with swapped dispatch arguments)
 *
 * https://symfony.com/blog/new-in-symfony-4-3-simpler-event-dispatching
 */
abstract class LegacyAbstractEventDispatcherDecorator implements EventDispatcherInterface
{
    use AbstractEventDispatcherDecoratorTrait;

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, Event $event =  null): object
    {
        return $this->dispatcher->dispatch($eventName, $event);
    }
}
