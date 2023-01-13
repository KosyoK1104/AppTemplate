<?php

declare(strict_types=1);

use App\Console\Commands\Create;
use App\Console\Commands\Migrate;
use App\Console\Commands\Rollback;
use App\Console\Commands\Status;

return [
    Migrate::class,
    Create::class,
    Rollback::class,
    Status::class,
];
