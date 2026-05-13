<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\Date;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AttendancesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('attendances');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Classes', [
            'foreignKey' => 'class_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('MarkedByUsers', [
            'className' => 'Users',
            'foreignKey' => 'marked_by',
            'propertyName' => 'marked_by_user',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('student_id')
            ->requirePresence('student_id', 'create')
            ->notEmptyString('student_id');

        $validator
            ->integer('class_id')
            ->requirePresence('class_id', 'create')
            ->notEmptyString('class_id');

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDate('date');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->requirePresence('status', 'create')
            ->notEmptyString('status')
            ->inList('status', ['present', 'absent', 'late', 'excused', 'half_day']);

        $validator
            ->time('check_in_time')
            ->allowEmptyTime('check_in_time');

        $validator
            ->time('check_out_time')
            ->allowEmptyTime('check_out_time');

        $validator
            ->scalar('remarks')
            ->allowEmptyString('remarks');

        return $validator;
    }

    public function findByStudent(SelectQuery $query, int $studentId): SelectQuery
    {
        return $query
            ->where(['Attendances.student_id' => $studentId])
            ->orderByDesc('Attendances.date');
    }

    public function findByDate(SelectQuery $query, Date|string $date): SelectQuery
    {
        return $query->where(['Attendances.date' => $date]);
    }

    public function findByClass(SelectQuery $query, int $classId): SelectQuery
    {
        return $query->where(['Attendances.class_id' => $classId]);
    }

    public function findByMonth(SelectQuery $query, int $year, int $month): SelectQuery
    {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        return $query->where([
            'Attendances.date >=' => $startDate,
            'Attendances.date <=' => $endDate,
        ]);
    }

    public function getStudentSummary(int $studentId, ?int $year = null, ?int $month = null): array
    {
        $conditions = ['Attendances.student_id' => $studentId];

        if ($year && $month) {
            $startDate = sprintf('%04d-%02d-01', $year, $month);
            $endDate = date('Y-m-t', strtotime($startDate));
            $conditions['Attendances.date >='] = $startDate;
            $conditions['Attendances.date <='] = $endDate;
        } elseif ($year) {
            $conditions['YEAR(Attendances.date)'] = $year;
        }

        $query = $this->find()
            ->select([
                'status' => 'Attendances.status',
                'count' => $this->find()->func()->count('*'),
            ])
            ->where($conditions)
            ->groupBy('Attendances.status');

        $results = $query->all()->combine('status', 'count')->toArray();

        $total = array_sum($results);
        $present = ($results['present'] ?? 0) + ($results['late'] ?? 0) + ($results['half_day'] ?? 0);

        return [
            'present' => $results['present'] ?? 0,
            'absent' => $results['absent'] ?? 0,
            'late' => $results['late'] ?? 0,
            'excused' => $results['excused'] ?? 0,
            'half_day' => $results['half_day'] ?? 0,
            'total' => $total,
            'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
        ];
    }

    public function getClassSummaryByDate(int $classId, Date|string $date): array
    {
        $query = $this->find()
            ->select([
                'status' => 'Attendances.status',
                'count' => $this->find()->func()->count('*'),
            ])
            ->where([
                'Attendances.class_id' => $classId,
                'Attendances.date' => $date,
            ])
            ->groupBy('Attendances.status');

        return $query->all()->combine('status', 'count')->toArray();
    }

    public function getMonthlyTrend(int $studentId, int $year): array
    {
        $trend = [];
        for ($month = 1; $month <= 12; $month++) {
            $summary = $this->getStudentSummary($studentId, $year, $month);
            $trend[$month] = $summary;
        }
        return $trend;
    }
}
