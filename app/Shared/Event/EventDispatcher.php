<?php

declare(strict_types=1);

namespace App\Shared\Event;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class EventDispatcher
{
    /**
     * @var array<class-string, EventListener> $listeners
     */
    private array $listeners = [];

    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    /**
     * @param array<class-string> $listeners
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function addListners(array $listeners) : void
    {
        foreach ($listeners as $listener) {
            $this->addListener($listener);
        }
    }

    /**
     * @param string $listener
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function addListener(string $listener) : void
    {
        $implements = class_implements($listener);
        if (in_array(EventListener::class, $implements, true)) {
            $this->listeners[$listener] = $this->container->get($listener);
        }
    }

    public function dispatch(Event $event) : void
    {
        foreach ($this->listeners as $listener) {
            if ($listener->isSubscribedTo($event)) {
                $listener->handle($event);
            }
        }
    }

    /**
     * @param Event[]|WithEvents $events
     * @return void
     */
    public function dispatchEvents(array|WithEvents $events) : void
    {
        if ($events instanceof WithEvents) {
            $events = $events->events();
        }
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }
}
