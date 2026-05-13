<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CoursesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('courses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Subjects', [
            'foreignKey' => 'subject_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Classes', [
            'foreignKey' => 'class_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Teachers', [
            'className' => 'Users',
            'foreignKey' => 'teacher_id',
            'propertyName' => 'teacher',
        ]);

        $this->hasMany('StudentCourses', [
            'foreignKey' => 'course_id',
            'dependent' => true,
        ]);

        $this->hasMany('CourseMaterials', [
            'foreignKey' => 'course_id',
            'dependent' => true,
        ]);

        $this->belongsToMany('Students', [
            'className' => 'Users',
            'through' => 'StudentCourses',
            'foreignKey' => 'course_id',
            'targetForeignKey' => 'student_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('subject_id')
            ->requirePresence('subject_id', 'create')
            ->notEmptyString('subject_id');

        $validator
            ->integer('class_id')
            ->requirePresence('class_id', 'create')
            ->notEmptyString('class_id');

        $validator
            ->integer('teacher_id')
            ->allowEmptyString('teacher_id');

        $validator
            ->scalar('name')
            ->maxLength('name', 150)
            ->allowEmptyString('name');

        $validator
            ->scalar('academic_year')
            ->maxLength('academic_year', 20)
            ->requirePresence('academic_year', 'create')
            ->notEmptyString('academic_year');

        $validator
            ->scalar('term')
            ->maxLength('term', 20)
            ->allowEmptyString('term');

        $validator
            ->integer('max_students')
            ->allowEmptyString('max_students');

        $validator
            ->boolean('is_active');

        return $validator;
    }

    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['Courses.is_active' => true]);
    }

    public function findByClass(SelectQuery $query, int $classId): SelectQuery
    {
        return $query->where(['Courses.class_id' => $classId]);
    }

    public function findByTeacher(SelectQuery $query, int $teacherId): SelectQuery
    {
        return $query->where(['Courses.teacher_id' => $teacherId]);
    }

    public function findByAcademicYear(SelectQuery $query, string $year): SelectQuery
    {
        return $query->where(['Courses.academic_year' => $year]);
    }

    public function getEnrolledCount(int $courseId): int
    {
        return $this->StudentCourses->find()
            ->where([
                'course_id' => $courseId,
                'status' => 'enrolled',
            ])
            ->count();
    }

    public function getTeacherCoursesSummary(int $teacherId, ?string $academicYear = null): array
    {
        $query = $this->find()
            ->contain(['Subjects', 'Classes'])
            ->where(['Courses.teacher_id' => $teacherId]);

        if ($academicYear) {
            $query->where(['Courses.academic_year' => $academicYear]);
        }

        $courses = $query->all();
        $summary = [];

        foreach ($courses as $course) {
            $enrolledCount = $this->getEnrolledCount($course->id);
            $summary[] = [
                'course' => $course,
                'enrolled_count' => $enrolledCount,
            ];
        }

        return $summary;
    }
}
