SPARK
=====

Spark is an application instrumentation & configuration library.

It is used to extract relevant information from the application for external processing.

For every Request + Response (Transaction), a set of Collectors are registered that capture detailed reports about every transaction.

Using one of the available Reporters, you can forward the extracted data to an external service for further processing.

## Use-cases

* Integrare Spark into your application, and handle logging, performance monitoring, metrics, event processing, exception tracking, etc outside of your application
* Report application events to an event bus for further processing
* Forward exceptions and logs to a central service that can analyze, store or forward them using any custom backends.
* Analyze app transactions to collect metrics

By integrating Spark into your application, you no longer need to individually add instrumentation and other generic application services to each application.

## Collectors

### Existing collectors:

* HTTP: Captures full HTTP Request and Response details, including query and post arguments, headers, attributes and server variables (environment)
* EventDispatcher: Captures all events that are dispatched during the transaction with name, payload and timestamp
* DoctrineSQLLogger: Captures all queries performed through a DBAL connection
* Logger: Captures all PSR-3 log messages
* Exception: Captures all exceptions with stack traces

### Future collectors:

* Guzzle: Capture all HTTP Requests performed by the application
* PDO: Raw PDO query collector
* Cache: Capture Cache hits and misses
* Stopwatch: Capture detailed time spans

## Reporters

At the end of the transaction, the collected information can be reported to one of serveral backends.

### Existing reporters:

* EchoReporter: Echos the data as pretty printed JSON in a code tag at the end of the page
* NdJsonReporter: Writes the data as NDJSON lines to a file
* GuzzleReporter: Submit the data as JSON using an HTTP POST

### Future reporters:

* NATS
* Kafka
* Local spool (for batch optimisation)

## Usage

Install Spark in your project:

    composer require linkorb/spark

### Symfony 4 / 5

In `services.yaml` register Spark with the following configuration lines:

```yaml
services:
    # Register a Spark instance
    Spark\Spark:
        public: true
        factory: ['Spark\Spark', 'getInstance']

    # Decorate the Symfony EventDispatcher to capture event data
    # Be sure to select the dispatcher that matches your version of the event dispatcher
    Spark\EventDispatcher\SparkEventDispatcherV5:
        decorates: 'event_dispatcher'

    # Capture PSR-3 Log data
    Spark\Logger\SparkLogger:
        decorates: 'logger'

    # Capture Doctrine DBAL data
    Spark\Doctrine\SparkDoctrineEventSubscriber:
        tags:
            - { name: doctrine.event_subscriber, connection: default }
    Spark\Doctrine\SparkSQLLogger:
        public: true

    # Register Event Subscriber to automatically report data on
    # kernel.terminate and capture kernel.exception data
    Spark\EventSubscriber\SparkEventSubscriber:
        public: true


```

Since Symfony's EventDispatcher method signatures have change throughout versions, multiple decorators for V3, V4 and V5 are available.

Please refer to src/EventDispatcher/README.md for details

### Other frameworks / libraries

1. Instantiate a spark instance using `$spark = \Spark\Spark::getInstance()`
2. Register collectors by decorating EventDispatcher, Logger, etc
3. After the transaction completes, call $spark->report();

### Whitelisting reported keys

By default, Spark reports all the keys in the collected data for each transaction.
You can apply a whitelist filter to limit the reported data by specifying `SPARK_REPORT_WHITELIST` with an array of patterns. For example:

    SPARK_REPORT_WHITELIST=id,type,http.response.*,queries.*

## License

MIT. Please refer to the [license file](LICENSE) for details.

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
