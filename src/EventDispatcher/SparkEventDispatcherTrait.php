<?php

namespace Spark\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Spark\Spark;

// Reusable trait for SparkEventDispatcher and LegacySparkEventDispatcherTrait
trait SparkEventDispatcherTrait
{

    protected $dispatcher;
    protected $transaction;
    protected $events = [];

    public function __construct(
        EventDispatcherInterface $dispatcher,
        Spark $spark
    )
    {
        $this->transaction = $spark->getTransaction();
        $this->transaction->registerCollector($this);
        parent::__construct($dispatcher);
    }

    public function getData(): array
    {
        $data = [];
        foreach ($this->events as $event) {
            $data[] = [
                'name' => $event[1],
                'payload' => $this->getEventPayload($event[0]),
                'stamp' => $event[2],
            ];
        }
        return ['events' => $data];
    }

    protected function getEventPayload($event): array
    {
        $payload = [];
        if (is_a($event, SparkEvent::class)) {
            return $event->getPayload();
        }
        if (is_object($event)) {
            foreach ($event as $key=>$value) {
                $payload[$key] = $value;
            }
        }
        return $payload;
    }
}
