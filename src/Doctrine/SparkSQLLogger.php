<?php

namespace Spark\Doctrine;

use Doctrine\DBAL\Logging\SQLLogger;
use Spark\Spark;

class SparkSQLLogger implements SQLLogger
{
    protected $spark;
    protected $transaction;

    public function __construct(Spark $spark)
    {
        $this->spark = $spark;
        $this->transaction = $spark->getTransaction();
        $this->transaction->registerCollector($this);
    }

    public $queries = [];

    /** @var float|null */
    public $start = null;

    /** @var int */
    public $currentQuery = 0;

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, ?array $params = null, ?array $types = null)
    {
        $this->start = microtime(true);
        $this->queries[++$this->currentQuery] = ['sql' => $sql, 'params' => $params, 'types' => $types, 'duration' => 0, 'start' => $this->start];
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {
        $this->queries[$this->currentQuery]['duration'] = microtime(true) - $this->start;
        $this->queries[$this->currentQuery]['end'] = microtime(true);
    }

    public function getData()
    {
        $data = [
            'queries' => []
        ];
        foreach ($this->queries as $query) {
            $data['queries'][] = $query;
        }
        return $data;
    }
}
