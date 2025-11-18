<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreatePosts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('posts');
        $table
            ->addColumn('title', 'string', [
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('body', 'text', [
                'null' => false,
            ])
            ->addColumn('published', 'boolean', [
                'default' => false,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addIndex(['slug'], ['unique' => true])
            ->create();
    }
}

