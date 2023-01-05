<?php

declare(strict_types=1);

use App\Commands\Create;
use App\Commands\Migrate;
use App\Commands\Rollback;
use App\Commands\Status;

return [
    Migrate::class,
    Create::class,
    Rollback::class,
    Status::class
];
