<?php

namespace Spark\Logger;

use Spark\Spark;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;


class SparkLogger implements LoggerInterface
{
    protected $logs = [];

    public function __construct(Spark $spark)
    {
        $this->transaction = $spark->getTransaction();
        $this->transaction->registerCollector($this);
    }

    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $this->logs[] = [
            $level,
            $message,
            $context,
        ];
    }

    public function getData(): array
    {
        $data = [];
        foreach ($this->logs as $log) {
            $data[] = [
                'level' => $log[0],
                'message' => $log[1],
                'context' => $log[2],
            ];
        }
        return ['logs' => $data];
    }
}
