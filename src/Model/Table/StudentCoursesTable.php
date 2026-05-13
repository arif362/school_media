<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class StudentCoursesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('student_courses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('student_id')
            ->requirePresence('student_id', 'create')
            ->notEmptyString('student_id');

        $validator
            ->integer('course_id')
            ->requirePresence('course_id', 'create')
            ->notEmptyString('course_id');

        $validator
            ->date('enrolled_date')
            ->requirePresence('enrolled_date', 'create')
            ->notEmptyDate('enrolled_date');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->inList('status', ['enrolled', 'completed', 'dropped', 'failed']);

        $validator
            ->scalar('grade')
            ->maxLength('grade', 10)
            ->allowEmptyString('grade');

        $validator
            ->decimal('marks')
            ->allowEmptyString('marks');

        return $validator;
    }

    public function findEnrolled(SelectQuery $query): SelectQuery
    {
        return $query->where(['StudentCourses.status' => 'enrolled']);
    }

    public function findByStudent(SelectQuery $query, int $studentId): SelectQuery
    {
        return $query->where(['StudentCourses.student_id' => $studentId]);
    }

    public function getStudentCourseSummary(int $studentId): array
    {
        $enrollments = $this->find()
            ->contain(['Courses' => ['Subjects', 'Classes', 'Teachers']])
            ->where(['StudentCourses.student_id' => $studentId])
            ->all();

        $summary = [
            'total' => 0,
            'enrolled' => 0,
            'completed' => 0,
            'courses' => [],
        ];

        foreach ($enrollments as $enrollment) {
            $summary['total']++;
            if ($enrollment->status === 'enrolled') {
                $summary['enrolled']++;
            } elseif ($enrollment->status === 'completed') {
                $summary['completed']++;
            }
            $summary['courses'][] = $enrollment;
        }

        return $summary;
    }
}
