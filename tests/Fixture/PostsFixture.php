<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PostsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'title' => 'Welcome to CakePHP',
                'slug' => 'welcome-to-cakephp',
                'body' => 'This is your first blog entry. Edit or delete it, then start writing!',
                'published' => true,
                'created' => '2025-11-17 00:00:00',
                'modified' => '2025-11-17 00:00:00',
            ],
        ];
        parent::init();
    }
}

