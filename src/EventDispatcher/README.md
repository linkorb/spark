Multiple event dispatcher decorators
====================================

The Symfony EventDispatcher has gone through a couple of method signatures.

In order to support apps using different versions of the event dispatcher, version-specific decorators are provided

Signatures and interfaces even changed throughout major symfony versions (i.e. 4.3). So this library only supports the latest version of the event dispatcher within those major numbers (i.e. v4 supports v4.4, not v4.2 and below)

## More info:

* https://symfony.com/blog/new-in-symfony-4-3-simpler-event-dispatching

### SparkEventDispatcherV5 (v5.1.3+):

* https://github.com/symfony/symfony/blob/v5.1.3/src/Symfony/Component/EventDispatcher/EventDispatcherInterface.php
* https://github.com/symfony/symfony/blob/v5.1.3/src/Symfony/Component/EventDispatcher/EventDispatcher.php
* public function dispatch(object $event, string $eventName = null): object;
* public function addListener(string $eventName, $listener, int $priority = 0);

### SparkEventDispatcherV4 (for v4.4.11+)

* https://github.com/symfony/symfony/blob/v4.4.11/src/Symfony/Component/EventDispatcher/EventDispatcherInterface.php
* https://github.com/symfony/symfony/blob/v4.4.11/src/Symfony/Component/EventDispatcher/EventDispatcher.php
* public function dispatch($event/ *, string $eventName = null* /);
* public function addListener($eventName, $listener, $priority = 0);

### SparkEventDispatcherV3 (for v3.4.43+)

* https://github.com/symfony/symfony/blob/v3.4.43/src/Symfony/Component/EventDispatcher/EventDispatcherInterface.php
* https://github.com/symfony/symfony/blob/v3.4.43/src/Symfony/Component/EventDispatcher/EventDispatcher.php
* public function dispatch($eventName, Event $event = null);
* public function addListener($eventName, $listener, $priority = 0);
