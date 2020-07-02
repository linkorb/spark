<?php
namespace Spark\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Spark\Spark;

class SparkServiceProvider implements
    ServiceProviderInterface,
    EventListenerProviderInterface
{
    public function register(Container $app)
    {
        // Wrap any existing dispatcher
        $app->extend(
            'dispatcher',
            function (
                $dispatcher,
                Application $app
            ) {
                return new \Spark\SparkEventDispatcher(
                    $dispatcher,
                    $app['spark.service']
                );
            }
        );

        $app['spark.service'] = function ($app) {
            // if (!isset($app['spark.dsn'])) {
            //     throw new RuntimeException(
            //         'You must set the "spark.dsn" container parameter in order to use the SparkServiceProvider.'
            //     );
            // }
            return new SparkService();
        };
    }
    /**
     * Subscribe SentryService.
     *
     * {@inheritdoc}
     */
    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber($app['spark.service']);
    }
}
