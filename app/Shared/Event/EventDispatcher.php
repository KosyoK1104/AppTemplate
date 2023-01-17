<?php

declare(strict_types=1);

namespace App\Shared\Event;

use App\Shared\Event\Exceptions\EventingException;
use Psr\Container\ContainerInterface;

final class EventDispatcher
{

    /**
     * @param array<class-string, array<class-string>> $listeners
     */
    private array $listeners = [];

    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    /**
     * @param array<class-string, array<class-string>> $listeners
     * @return void
     */
    public function addListeners(array $listeners = []) : void
    {
        foreach ($listeners as $event => $listener) {
            foreach ($listener as $listenerClass) {
                $this->addListener($event, $listenerClass);
            }
        }
    }

    /**
     * @param class-string $event
     * @param class-string $listener
     * @return void
     */
    public function addListener(string $event, string $listener) : void
    {
        if (!in_array(Event::class, class_implements($event), true)) {
            throw new EventingException(sprintf('Class %s must implement %s', $event, Event::class));
        }

        if (!in_array(EventListener::class, class_implements($listener), true)) {
            throw new EventingException(sprintf('Class %s must implement %s', $listener, EventListener::class));
        }

        $this->listeners[$event][] = $listener;
    }

    public function dispatch(RecordEvents $recordEvents) : void
    {
        foreach ($recordEvents->events() as $event) {
            $this->dispatchEvent($event);
        }
    }

    public function dispatchEvent(Event $event) : void
    {
        $eventClass = get_class($event);
        if (isset($this->listeners[$eventClass])) {
            foreach ($this->listeners[$eventClass] as $listenerClass) {
                $listener = $this->container->get($listenerClass);
                $listener->handle($event);
            }
        }
    }

    /**
     * @param array $events
     * @return void
     */
    public function dispatchEvents(array $events) : void
    {
        foreach ($events as $event) {
            $this->dispatchEvent($event);
        }
    }
}
