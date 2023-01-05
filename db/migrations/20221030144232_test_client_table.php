<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TestClientTable extends AbstractMigration
{

    public function up() : void
    {
        $this->table('clients')
            ->addColumn('first_name', 'string')
            ->addColumn('last_name', 'string')
            ->create()
        ;
    }

    public function down() : void
    {
        $this->table('clients')
            ->drop()
            ->save()
        ;
    }
}
