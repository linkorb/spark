<?php

namespace Spark\Reporter;

use GuzzleHttp\Client as GuzzleClient;

class NdJsonFileReporter implements ReporterInterface
{
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }
    public function report(array $data): void
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES);
        $fp = fopen($this->filename, 'a');
        fwrite($fp, $json . PHP_EOL);
        fclose($fp);
    }
}
