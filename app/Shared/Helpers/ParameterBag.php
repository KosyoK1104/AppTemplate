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
}
