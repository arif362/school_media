<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class UserNotification extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'notification_id' => true,
        'is_read' => true,
        'read_at' => true,
        'created' => true,
    ];
}
