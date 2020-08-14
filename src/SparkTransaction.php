<?php

namespace Spark;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;

class SparkTransaction
{
    protected $id;
    protected $startStamp;
    protected $collectors = [];
    protected $requestStack;
    protected $exceptions = [];
    protected $httpRequest;
    protected $httpResponse;


    public function __construct(string $id, float $startStamp)
    {
        $this->startStamp = $startStamp;
        $this->id = $id;
    }

    public function setHttpRequest(Request $request): void
    {
        $this->httpRequest = $request;
    }
    public function setHttpResponse(Response $response): void
    {
        $this->httpResponse = $response;
    }

    public function registerCollector($collector): void
    {
        $this->collectors[] = $collector;
    }

    public function addException($exception): void
    {
        $this->exceptions[] = $exception;
    }

    public function serialize(): array
    {
        $endStamp = microtime(true);
        $data = [
            'type' => 'transaction',
            'id' => $this->id,
            'start' => $this->startStamp,
            'end' => $endStamp,
            'duration' => ($endStamp - $this->startStamp),
            'http' => [
                'request' => [],
                'response' => [],
            ]
        ];

        if ($this->httpRequest) {
            $data['http']['request']['method'] = $this->httpRequest->getMethod();
            $data['http']['request']['host'] = $this->httpRequest->getHost();
            $data['http']['request']['path'] = $this->httpRequest->getPathInfo();
            $data['http']['request']['clientIp'] = $this->httpRequest->getClientIp();
            $data['http']['request']['query'] = $this->httpRequest->query->all();
            $data['http']['request']['post'] = $this->httpRequest->request->all();
            $data['http']['request']['headers'] = $this->httpRequest->headers->all();
            $data['http']['request']['attributes'] = $this->httpRequest->attributes->all();
            $data['http']['request']['server'] = $this->httpRequest->server->all();
        }
        if ($this->httpResponse) {
            $data['http']['response']['status'] = $this->httpResponse->getStatusCode();
            $data['http']['response']['headers'] = $this->httpResponse->headers->all();
            // $data['response']['content'] = $response->getContent();
        }

        foreach ($this->collectors as $collector) {
            $data = array_merge($data, $collector->getData());
        }
        foreach ($this->exceptions as $exception) {
            $data['exceptions'][] = $this->serializeException($exception);
        }
        return $data;
    }

    protected function serializeException($exception): array
    {
        $data = [];
        $data['code'] = $exception->getCode();
        $data['class'] = get_class($exception);
        $data['message'] = $exception->getMessage();
        $data['file'] = $exception->getFile();
        $data['line'] = $exception->getLine();
        $data['trace'] = $exception-> getTraceAsString();
        if ($exception instanceof HttpExceptionInterface) {
            $data['httpCode'] = $exception->getStatusCode();
        }
        return $data;
    }
}
