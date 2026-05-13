<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class StudentFeesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('student_fees');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
        ]);

        $this->belongsTo('FeeTypes', [
            'foreignKey' => 'fee_type_id',
        ]);

        $this->hasMany('FeePayments', [
            'foreignKey' => 'student_fee_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('student_id')
            ->requirePresence('student_id', 'create')
            ->notEmptyString('student_id');

        $validator
            ->integer('fee_type_id')
            ->requirePresence('fee_type_id', 'create')
            ->notEmptyString('fee_type_id');

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->date('due_date')
            ->requirePresence('due_date', 'create')
            ->notEmptyDate('due_date');

        return $validator;
    }

    public function getStudentFeesSummary(int $studentId, ?string $academicYear = null): array
    {
        $conditions = ['StudentFees.student_id' => $studentId];
        if ($academicYear) {
            $conditions['StudentFees.academic_year'] = $academicYear;
        }

        $fees = $this->find()
            ->contain(['FeeTypes', 'FeePayments'])
            ->where($conditions)
            ->orderBy(['StudentFees.due_date' => 'ASC'])
            ->all();

        $summary = [
            'total_fees' => 0,
            'total_discount' => 0,
            'total_paid' => 0,
            'total_pending' => 0,
            'fees' => [],
        ];

        foreach ($fees as $fee) {
            $netAmount = $fee->amount - $fee->discount;
            $paidAmount = 0;

            foreach ($fee->fee_payments as $payment) {
                $paidAmount += (float)$payment->amount;
            }

            $pendingAmount = max(0, $netAmount - $paidAmount);

            $summary['total_fees'] += $fee->amount;
            $summary['total_discount'] += $fee->discount;
            $summary['total_paid'] += $paidAmount;
            $summary['total_pending'] += $pendingAmount;

            $summary['fees'][] = [
                'fee' => $fee,
                'net_amount' => $netAmount,
                'paid_amount' => $paidAmount,
                'pending_amount' => $pendingAmount,
            ];
        }

        return $summary;
    }
}
