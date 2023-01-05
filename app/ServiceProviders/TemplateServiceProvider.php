<?php

declare(strict_types=1);

namespace App\ServiceProviders;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TemplateServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id): bool
    {
        return $id === Environment::class;
    }

    public function register(): void
    {
        $this->container->addShared(Environment::class, function () {
            $loader = new FilesystemLoader(ROOT_DIR . '/templates');
            $twig = new Environment($loader);

            return $twig;
        });
    }
}