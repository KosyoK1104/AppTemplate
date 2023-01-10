<?php

declare(strict_types=1);

namespace App\Shared\Collection;

use App\Shared\Collection\Exceptions\TrackedCollectionException;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @template T
 * @extends TrackedCollection<T>
 * @implements IteratorAggregate<int, T>
 * @implements JsonSerializable<T>
 * @implements Countable<int>
 */
abstract class TrackedCollection implements Countable, IteratorAggregate, JsonSerializable
{
    /**
     * @var array<int, T>
     * @psalm-var array<int, T>
     */
    private array $clean;
    /**
     * @var array<int, T>
     */
    private array $dirty;
    /**
     * @var array<int, T>
     */
    private array $trashed;

    /**
     * @param array<int, T> $items
     */
    public function __construct(array $items = [])
    {
        $this->guard($items);
        $this->clean = $items;
        $this->dirty = [];
        $this->trashed = [];
    }

    protected function guard(mixed $items) : void
    {
        if (!is_array($items)) {
            $items = [$items];
        }
        if ($this->isEmpty()) {
            $type = null;
        }
        else {
            $type = get_class($this->first());
        }
        foreach ($items as $item) {
            if ($type === null) {
                $type = get_class($item);
            }
            if (get_class($item) !== $type) {
                throw new TrackedCollectionException('All items must be of the same type');
            }
        }
    }

    public function items() : array
    {
        return array_merge($this->clean, $this->dirty);
    }

    public function count() : int
    {
        return count($this->items());
    }

    public function isEmpty() : bool
    {
        return empty($this->items());
    }

    public function hasDirty() : bool
    {
        return !empty($this->dirty);
    }

    public function hasTrashed() : bool
    {
        return !empty($this->trashed);
    }

    public function clean() : array
    {
        return $this->clean;
    }

    public function dirty() : array
    {
        return $this->dirty;
    }

    public function trashed() : array
    {
        return $this->trashed;
    }

    public function add(mixed $item) : void
    {
        $this->guard($item);
        $this->dirty[] = $item;
    }

    public function remove(mixed $item) : void
    {
        $key = array_search($item, $this->clean, true);
        if ($key) {
            array_splice($this->clean, $key, 1);
            $this->trashed[] = $item;
        }
    }

    public function map(callable $callback) : array
    {
        return array_map($callback, $this->items());
    }

    public function filter(callable $callback) : array
    {
        return array_filter($this->items(), $callback);
    }

    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->items());
    }

    public function jsonSerialize() : array
    {
        return $this->items();
    }

    public function toArray() : array
    {
        return $this->items();
    }

    public function first() : mixed
    {
        return $this->clean[0];
    }
}
