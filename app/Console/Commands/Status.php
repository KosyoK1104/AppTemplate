<?php

declare(strict_types=1);

namespace App\Console\Commands;

final class Status extends \Phinx\Console\Command\Status
{
    protected function configure() : void
    {
        parent::configure();
        $this->setName('migration:status');
    }
}
