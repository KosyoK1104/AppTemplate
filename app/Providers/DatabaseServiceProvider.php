<?php

declare(strict_types=1);

namespace App\Providers;

use App\Shared\Database\Database;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class DatabaseServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id) : bool
    {
        return $id === Database::class;
    }

    public function register() : void
    {
        $this->container->addShared(
            Database::class,
            function () {
                $db = new Database(env('DB_NAME'), env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'));
                $db->beginTransaction();
                return $db;
            }
        );
    }
}
