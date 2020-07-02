<?php

namespace Spark\Reporter;

use GuzzleHttp\Client as GuzzleClient;

class EchoReporter implements ReporterInterface
{
    public function report(array $data): void
    {
        echo '<hr /><pre><code>' . json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) . '</code></pre>';
    }
}
