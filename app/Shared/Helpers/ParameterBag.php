<?php

declare(strict_types=1);

namespace App\Shared\Helpers;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class ParameterBag implements IteratorAggregate
{
    public function __construct(
        private readonly array $items = []
    ) {
    }

    public function array(string $index) : array
    {
        if (is_array($this->items[$index])) {
            return $this->items[$index];
        }
        return [];
    }

    public function bag(string $index) : self
    {
        if (is_array($this->items[$index])) {
            return new self($this->items[$index]);
        }
        return new self();
    }

    public function string(string $index, mixed $default = '') : string
    {
        if (array_key_exists($index, $this->items)) {
            return (string) $this->items[$index];
        }
        return $default;
    }

    public function stringOrNull(string $index) : ?string
    {
        if (array_key_exists($index, $this->items)) {
            return (string) $this->items[$index];
        }
        return null;
    }

    public function int(string $index, int $default = 0) : int
    {
        if (array_key_exists($index, $this->items)) {
            return (int) $this->items[$index];
        }
        return $default;
    }

    public function intOrNull(string $index) : ?int
    {
        if (array_key_exists($index, $this->items)) {
            return (int) $this->items[$index];
        }
        return null;
    }

    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function boolean(string $index, bool $default = false) : bool
    {
        if (array_key_exists($index, $this->items)) {
            return (bool) $this->items[$index];
        }
        return $default;
    }

    public function booleanOrNull(string $index) : ?bool
    {
        if (array_key_exists($index, $this->items)) {
            return (bool) $this->items[$index];
        }
        return null;
    }

    public function float(string $index, float $default = 0.0) : float
    {
        if (array_key_exists($index, $this->items)) {
            return (float) $this->items[$index];
        }
        return $default;
    }

    public function floatOrNull(string $index) : ?float
    {
        if (array_key_exists($index, $this->items)) {
            return (float) $this->items[$index];
        }
        return null;
    }

    public function all() : array
    {
        return $this->items;
    }

    public function has(string $index) : bool
    {
        return array_key_exists($index, $this->items);
    }

    public function hasNot(string $index) : bool
    {
        return !$this->has($index);
    }
}
