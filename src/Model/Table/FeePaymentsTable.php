<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class FeePaymentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('fee_payments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('StudentFees', [
            'foreignKey' => 'student_fee_id',
        ]);

        $this->belongsTo('ReceivedByUsers', [
            'className' => 'Users',
            'foreignKey' => 'received_by',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('student_fee_id')
            ->requirePresence('student_fee_id', 'create')
            ->notEmptyString('student_fee_id');

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->date('payment_date')
            ->requirePresence('payment_date', 'create')
            ->notEmptyDate('payment_date');

        $validator
            ->scalar('payment_method')
            ->requirePresence('payment_method', 'create')
            ->notEmptyString('payment_method');

        return $validator;
    }

    public function beforeSave($event, $entity, $options)
    {
        // Generate receipt number if not provided
        if ($entity->isNew() && empty($entity->receipt_number)) {
            $entity->receipt_number = 'RCP-' . date('Ymd') . '-' . str_pad((string)random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        return true;
    }
}
