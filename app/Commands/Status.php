<?php

declare(strict_types=1);

namespace App\Commands;

final class Status extends \Phinx\Console\Command\Status
{
    protected function configure()
    {
        parent::configure();
        $this->setName('migration:status');
    }
}