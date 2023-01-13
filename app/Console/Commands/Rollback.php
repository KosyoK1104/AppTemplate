<?php

declare(strict_types=1);

namespace App\Console\Commands;

final class Rollback extends \Phinx\Console\Command\Rollback
{
    protected function configure() : void
    {
        parent::configure();
        $this->setName('migration:rollback');
    }
}
