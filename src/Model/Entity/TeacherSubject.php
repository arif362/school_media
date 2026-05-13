<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class TeacherSubject extends Entity
{
    protected array $_accessible = [
        'teacher_id' => true,
        'subject_id' => true,
        'is_primary' => true,
        'created' => true,
        'modified' => true,
        'teacher' => true,
        'subject' => true,
    ];
}
