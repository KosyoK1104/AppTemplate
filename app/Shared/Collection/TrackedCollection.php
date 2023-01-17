<?php

declare(strict_types=1);

namespace App\Shared\Collection;

use App\Shared\Collection\Exceptions\TrackedCollectionException;
use ArrayIterator;
use Countable;
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
     * @var class-string<T> $className
     */
    private string $className;

    /**
     * @param array<int, T> $items
     * @param class-string<T> $className
     */
    public function __construct(array $items, string $className)
    {
        $this->className = $className;
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
        foreach ($items as $item) {
            if (!is_a($item, $this->className)) {
                throw new TrackedCollectionException(sprintf('Item must be an instance of %s', $this->className));
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

    /**
     * @param class-string<T> $className
     * @return static<T>
     */
    public static function empty(string $className) : self
    {
        return new static([], $className);
    }
}
