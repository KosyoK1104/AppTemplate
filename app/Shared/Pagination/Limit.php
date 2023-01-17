<?php

declare(strict_types=1);

namespace App\Shared\Pagination;

use App\Shared\Pagination\Exceptions\InvalidPaginationException;
use JsonSerializable;

class Limit implements JsonSerializable
{
    public function __construct(public readonly int $limit, public readonly int $offset)
    {
        $this->guard($this->limit, $this->offset);
    }

    private function guard(int $limit, int $offset) : void
    {
        if ($limit < 1) {
            throw new InvalidPaginationException('Limit must be greater than 0');
        }
        if ($offset < 0) {
            throw new InvalidPaginationException('Offset must be greater than or equal to 0');
        }
    }

    public function toArray() : array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }
}
