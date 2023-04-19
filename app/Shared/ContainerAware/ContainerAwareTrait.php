<?php

declare(strict_types=1);

namespace App\Shared\ContainerAware;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;

trait ContainerAwareTrait
{
    public readonly ContainerInterface $container;

    public function __setContainer($container) : void
    {
        $this->container = $container;
    }

    public function container() : ContainerInterface
    {
        if (!isset($this->container)) {
            throw new InvalidArgumentException('Container is not set!');
        }
        return $this->container;
    }
}
