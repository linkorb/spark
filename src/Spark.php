<?php

namespace Spark;

use Spark\Reporter\GuzzleReporter;
use Spark\Reporter\NdJsonFileReporter;
use Spark\Reporter\EchoReporter;
use GuzzleHttp\Client as GuzzleClient;
use Xuid\Xuid;

class Spark
{
    protected $enabled = false;
    protected $reporter;
    protected $whitelist;

    protected static $instance;
    protected $transaction; // master transaction

    public static function getInstance(): self
    {
        if (!self::$instance) {
            $dsn = getenv('SPARK_DSN');
            $reporterName = getenv('SPARK_REPORTER');
            $reporter = null;
            $guzzle = null;
            if ($dsn) {
                $scheme = parse_url($dsn, PHP_URL_SCHEME);
                $host = parse_url($dsn, PHP_URL_HOST);
                $username = parse_url($dsn, PHP_URL_USER);
                $password = parse_url($dsn, PHP_URL_PASS);

                $url = $scheme . '://' . $host . '/api/v1/services/' . $username . '/';

                $guzzle = new GuzzleClient([
                    // Base URI is used with relative requests
                    'base_uri' => $url,
                    // You can set any number of default request options.
                    'timeout'  => 2.0,
                    'auth' => [$username, $password]
                ]);

                if ($reporterName=='guzzle') {
                    $reporter = new GuzzleReporter($guzzle, 'transactions');
                }
            }

            if ($reporterName=='ndjsonfile') {
                $reporter = new NdJsonFileReporter('/tmp/spark.ndjson');
            }
            if ($reporterName=='echo') {
                $reporter = new EchoReporter();
            }

            self::$instance = new self($guzzle, $reporter);
            self::$instance->whitelist = explode(',', getenv('SPARK_REPORT_WHITELIST'));
        }
        return self::$instance;
    }

    public function getTransaction(): SparkTransaction
    {
        if (!$this->transaction) {

            $this->transaction = new SparkTransaction(Xuid::getXuid(), microtime(true));
        }
        return $this->transaction;
    }

    public function getInstanceTransaction(): SparkTransaction
    {
        $spark = self::getInstance();
        return $spark->getTransaction();
    }

    protected function __construct($guzzle = null, $reporter = null)
    {
        $this->guzzle = $guzzle;
        $this->reporter = $reporter;
    }

    public function report(): void
    {
        $this->reportTransaction($this->getTransaction());
        // TODO: report app details (git hash, deploy stamp, etc)
        // TODO: report host details (hostname, disk usage, load, memory usage)
    }

    public function reportTransaction(SparkTransaction $transaction): void
    {
        if ($this->reporter) {
            $data = $this->transaction->serialize();
            $this->applyWhitelist('', $data, $this->whitelist);
            $this->reporter->report($data);
        }
    }

    protected function applyWhitelist(string $prefix, array &$data, array $whitelist)
    {
        foreach ($data as $k=>$v) {
            $key = trim($prefix . '.' . $k, '.');

            if (is_array($v)) {
                $this->applyWhitelist($key, $data[$k], $whitelist);
                if (count($data[$k])==0) {
                    unset($data[$k]);
                }
            } else {
                $match = false;
                foreach ($whitelist as $w) {
                    if (fnmatch($w, $key)) {
                        $match = true;
                    }
                }
                if (!$match) {
                    unset($data[$k]);
                }
            }
        }
    }


}
