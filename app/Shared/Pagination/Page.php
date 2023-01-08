<?php

declare(strict_types=1);

namespace App\Shared\Pagination;

final class Page extends Limit {

    public function __construct(public readonly int $page, public readonly int $size)
    {
        parent::__construct($size, ($page - 1) * $size);
    }

    public function toArray() : array
    {
        return [
            'page' => $this->page,
            'size' => $this->size,
        ];
    }

}
