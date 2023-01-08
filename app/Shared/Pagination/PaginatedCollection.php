<?php

declare(strict_types=1);

namespace App\Shared\Pagination;

use App\Shared\Collection\Collection;

final class PaginatedCollection extends Collection
{
    public function __construct(
        $items,
        public readonly Limit $limit,
    ) {
        parent::__construct($items);
    }

    public function toArray() : array
    {
        return [
            'items'      => $this->items(),
            'pagination' => $this->pagination()
        ];
    }

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    public function pagination() : array
    {
        return [
            'total_pages'  => ceil($this->count() / $this->limit->limit),
            'current_page' => $this->limit->offset / $this->limit->limit + 1,
            'total'        => $this->count(),
            'from'         => $this->limit->offset + 1,
            'to'           => $this->limit->offset + $this->limit->limit,
            'per_page'     => $this->limit->limit,
        ];
    }
}
