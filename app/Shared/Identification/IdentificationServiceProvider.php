<?php

declare(strict_types=1);

namespace App\Shared\Identification;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Ramsey\Uuid\UuidFactory;

final class IdentificationServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id) : bool
    {
        $provides = [
            UuidGeneratorInterface::class,
        ];

        return in_array($id, $provides, true);
    }

    public function register() : void
    {
        $this->container->addShared(UuidGeneratorInterface::class, function () {
            return new UuidGenerator(new UuidFactory());
        });
    }
}
