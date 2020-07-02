<?php

namespace Spark\EventDispatcher;

class SparkEvent
{
    protected $name;
    protected $payload;
    protected $stamp;

    public function __construct(string $name, array $payload, $stamp = null)
    {
        if (!$stamp) {
            $stamp = time();
        }
        $this->name = $name;
        $this->payload = $payload;
        $this->stamp = $stamp;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getStamp()
    {
        return $this->stamp;
    }
}
