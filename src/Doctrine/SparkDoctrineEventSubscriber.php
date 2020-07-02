<?php

namespace Spark\Doctrine;

use Doctrine\Common\EventSubscriber;
// use Doctrine\ORM\Events;
use Doctrine\DBAL\Events;
use Doctrine\DBAL\Event\ConnectionEventArgs;

class SparkDoctrineEventSubscriber implements EventSubscriber
{
    protected $logger;
    public function __construct(SparkSQLLogger $logger)
    {
        $this->logger = $logger;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postConnect
        ];
    }

    public function postConnect(ConnectionEventArgs $args)
    {
        $connection = $args->getConnection();
        $configuration = $connection->getConfiguration();
        $configuration->setSQLLogger($this->logger);
    }
}
