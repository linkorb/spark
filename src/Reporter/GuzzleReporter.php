<?php

namespace Spark\Reporter;

use GuzzleHttp\Client as GuzzleClient;

class GuzzleReporter implements ReporterInterface
{
    protected $path;

    public function __construct($guzzle, string $path)
    {
        $this->guzzle = $guzzle;
        $this->path = $path;
    }

    public function report(array $data): void
    {
        $this->guzzle->request(
            'POST',
            $this->path,
            [
                'json' => $data,
            ]
        );
    }
}
