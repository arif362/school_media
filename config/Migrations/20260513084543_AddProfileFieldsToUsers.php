<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddProfileFieldsToUsers extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('bio', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('avatar', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('phone', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
        ]);
        $table->addColumn('address', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('date_of_birth', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('grade_level', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
        ]);
        $table->update();
    }
}
