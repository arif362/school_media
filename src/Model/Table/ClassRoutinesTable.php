<?php
/**
 * ClassRoutines Table
 *
 * Manages class routine (timetable) entries linking classes, periods, subjects, and teachers.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\ClassRoutine;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ClassRoutinesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('class_routines');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Classes', [
            'foreignKey' => 'class_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Periods', [
            'foreignKey' => 'period_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Subjects', [
            'foreignKey' => 'subject_id',
        ]);

        $this->belongsTo('Teachers', [
            'className' => 'Users',
            'foreignKey' => 'teacher_id',
            'propertyName' => 'teacher',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('class_id')
            ->requirePresence('class_id', 'create')
            ->notEmptyString('class_id');

        $validator
            ->integer('period_id')
            ->requirePresence('period_id', 'create')
            ->notEmptyString('period_id');

        $validator
            ->integer('day_of_week')
            ->requirePresence('day_of_week', 'create')
            ->notEmptyString('day_of_week')
            ->range('day_of_week', [1, 7], __('Day must be between 1 (Monday) and 7 (Sunday)'));

        $validator
            ->integer('subject_id')
            ->allowEmptyString('subject_id');

        $validator
            ->integer('teacher_id')
            ->allowEmptyString('teacher_id');

        $validator
            ->scalar('room')
            ->maxLength('room', 50)
            ->allowEmptyString('room');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        $validator
            ->scalar('academic_year')
            ->maxLength('academic_year', 20)
            ->requirePresence('academic_year', 'create')
            ->notEmptyString('academic_year');

        $validator
            ->boolean('is_active');

        return $validator;
    }

    /**
     * Find active routines
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['ClassRoutines.is_active' => true]);
    }

    /**
     * Find routines by class and academic year
     */
    public function findByClass(SelectQuery $query, int $classId, ?string $academicYear = null): SelectQuery
    {
        $query->where(['ClassRoutines.class_id' => $classId]);

        if ($academicYear) {
            $query->where(['ClassRoutines.academic_year' => $academicYear]);
        }

        return $query;
    }

    /**
     * Find routines by teacher and academic year
     */
    public function findByTeacher(SelectQuery $query, int $teacherId, ?string $academicYear = null): SelectQuery
    {
        $query->where(['ClassRoutines.teacher_id' => $teacherId]);

        if ($academicYear) {
            $query->where(['ClassRoutines.academic_year' => $academicYear]);
        }

        return $query;
    }

    /**
     * Find routines by academic year
     */
    public function findByAcademicYear(SelectQuery $query, string $year): SelectQuery
    {
        return $query->where(['ClassRoutines.academic_year' => $year]);
    }

    /**
     * Find routines ordered by day and period
     */
    public function findOrdered(SelectQuery $query): SelectQuery
    {
        return $query
            ->contain(['Periods'])
            ->orderBy([
                'ClassRoutines.day_of_week' => 'ASC',
                'Periods.order_num' => 'ASC',
            ]);
    }

    /**
     * Get full routine grid for a class
     * Returns array indexed by [period_id][day_of_week] => routine entity
     */
    public function getRoutineGrid(int $classId, string $academicYear): array
    {
        $routines = $this->find()
            ->contain(['Periods', 'Subjects', 'Teachers'])
            ->find('byClass', classId: $classId, academicYear: $academicYear)
            ->find('active')
            ->all();

        $grid = [];
        foreach ($routines as $routine) {
            $grid[$routine->period_id][$routine->day_of_week] = $routine;
        }

        return $grid;
    }

    /**
     * Find teacher conflicts - check if teacher is assigned elsewhere at same time
     *
     * @param int $teacherId Teacher to check
     * @param int $periodId Period to check
     * @param int $dayOfWeek Day to check (1-7)
     * @param string $academicYear Academic year
     * @param int|null $excludeClassId Class to exclude from conflict check (for editing existing)
     * @return array List of conflicting routine entries
     */
    public function findConflicts(
        int $teacherId,
        int $periodId,
        int $dayOfWeek,
        string $academicYear,
        ?int $excludeClassId = null
    ): array {
        $query = $this->find()
            ->contain(['Classes', 'Periods', 'Subjects'])
            ->where([
                'ClassRoutines.teacher_id' => $teacherId,
                'ClassRoutines.period_id' => $periodId,
                'ClassRoutines.day_of_week' => $dayOfWeek,
                'ClassRoutines.academic_year' => $academicYear,
                'ClassRoutines.is_active' => true,
            ]);

        if ($excludeClassId) {
            $query->where(['ClassRoutines.class_id !=' => $excludeClassId]);
        }

        return $query->all()->toArray();
    }

    /**
     * Get routine completion percentage for a class
     * Compares filled slots vs total available slots (periods x days)
     */
    public function getCompletionPercentage(int $classId, string $academicYear): int
    {
        // Get total non-break periods for this academic year
        $periodsTable = $this->Periods;
        $totalPeriods = $periodsTable->find()
            ->where([
                'academic_year' => $academicYear,
                'is_break' => false,
                'is_active' => true,
            ])
            ->count();

        if ($totalPeriods === 0) {
            return 0;
        }

        // 5 weekdays
        $totalSlots = $totalPeriods * 5;

        // Count filled slots
        $filledSlots = $this->find()
            ->where([
                'class_id' => $classId,
                'academic_year' => $academicYear,
                'is_active' => true,
                'subject_id IS NOT' => null,
            ])
            ->count();

        return (int) round(($filledSlots / $totalSlots) * 100);
    }

    /**
     * Copy routine from one class to another
     *
     * @param int $sourceClassId Source class ID
     * @param int $targetClassId Target class ID
     * @param string $targetAcademicYear Target academic year
     * @param bool $copyTeachers Whether to copy teacher assignments
     * @return int Number of entries copied
     */
    public function copyRoutine(
        int $sourceClassId,
        int $targetClassId,
        string $targetAcademicYear,
        bool $copyTeachers = false
    ): int {
        $sourceRoutines = $this->find()
            ->where([
                'class_id' => $sourceClassId,
                'is_active' => true,
            ])
            ->all();

        $copied = 0;
        foreach ($sourceRoutines as $source) {
            // Check if target slot already exists
            $existing = $this->find()
                ->where([
                    'class_id' => $targetClassId,
                    'period_id' => $source->period_id,
                    'day_of_week' => $source->day_of_week,
                    'academic_year' => $targetAcademicYear,
                ])
                ->first();

            if ($existing) {
                continue; // Skip if slot already has an entry
            }

            $newRoutine = $this->newEntity([
                'class_id' => $targetClassId,
                'period_id' => $source->period_id,
                'day_of_week' => $source->day_of_week,
                'subject_id' => $source->subject_id,
                'teacher_id' => $copyTeachers ? $source->teacher_id : null,
                'room' => $source->room,
                'notes' => $source->notes,
                'academic_year' => $targetAcademicYear,
                'is_active' => true,
            ]);

            if ($this->save($newRoutine)) {
                $copied++;
            }
        }

        return $copied;
    }
}
