<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');
        $table
            ->addColumn('name', 'string', [
                'limit' => 120,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'limit' => 180,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('role', 'string', [
                'limit' => 20,
                'default' => 'student',
                'null' => false,
            ])
            ->addColumn('active', 'boolean', [
                'default' => true,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}

