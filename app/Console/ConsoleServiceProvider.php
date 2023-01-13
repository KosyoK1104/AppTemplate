<?php

declare(strict_types=1);

namespace App\Console;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\Console\Application;

final class ConsoleServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id) : bool
    {
        return $id === Application::class;
    }

    public function register() : void
    {
        $this->getContainer()->addShared(Application::class, function () {
            $app = new Application('Console', '1.0.0');
            $commands = require ROOT_DIR . '/app/Console/Commands/Commands.php';
            foreach ($commands as $command) {
                $app->add($this->getContainer()->get($command));
            }
            return $app;
        });
    }
}
