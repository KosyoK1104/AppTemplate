<?php

declare(strict_types=1);

namespace App\Shared\Pagination;

use JsonSerializable;

final class PaginatedCollection implements JsonSerializable
{
    public function __construct(
        public readonly array $items,
        public readonly Limit $limit,
    ) {
    }

    public function toArray() : array
    {
        return [
            'items'      => $this->items,
            'pagination' => $this->pagination(),
        ];
    }

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    public function pagination() : array
    {
        return [
            'total_pages'  => ceil(count($this->items) / $this->limit->limit),
            'current_page' => $this->limit->offset / $this->limit->limit + 1,
            'total'        => count($this->items),
            'from'         => $this->limit->offset + 1,
            'to'           => $this->limit->offset + $this->limit->limit,
            'per_page'     => $this->limit->limit,
        ];
    }
}
