<?php

namespace Spark\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Spark\Spark;

class SparkEventSubscriber implements EventSubscriberInterface
{
    protected $transaction;
    protected $spark;

    public function __construct(Spark $spark)
    {
        $this->spark = $spark;
        $this->transaction = $spark->getTransaction();
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => 'onTerminate',
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        $exception = $event->getThrowable();
        $this->transaction->addException($exception);
    }

    public function onTerminate(TerminateEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        $this->transaction->setHttpRequest($event->getRequest());
        $this->transaction->setHttpResponse($event->getResponse());

        $this->spark->reportTransaction($this->transaction);
    }
}
