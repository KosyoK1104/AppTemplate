<?php

declare(strict_types=1);

namespace App\Shared\ContainerAware;

use Psr\Container\ContainerInterface;

interface ContainerAwareInterface
{
    public function __setContainer(ContainerInterface $container) : void;

    public function container() : ContainerInterface;
}
