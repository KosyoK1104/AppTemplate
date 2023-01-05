<?php

declare(strict_types=1);

namespace App\Commands;

final class Rollback extends \Phinx\Console\Command\Rollback
{
    protected function configure()
    {
        parent::configure();
        $this->setName('migration:rollback');
    }
}