<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateStudentFees extends AbstractMigration
{
    public function change(): void
    {
        // Fee types table
        $feeTypes = $this->table('fee_types');
        $feeTypes
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('frequency', 'string', ['limit' => 20, 'default' => 'yearly']) // monthly, quarterly, yearly, one-time
            ->addColumn('is_mandatory', 'boolean', ['default' => true])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->create();

        // Student fees table (tracks individual student fee assignments and payments)
        $studentFees = $this->table('student_fees');
        $studentFees
            ->addColumn('student_id', 'integer')
            ->addColumn('fee_type_id', 'integer')
            ->addColumn('academic_year', 'string', ['limit' => 20])
            ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('discount', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => 0])
            ->addColumn('discount_reason', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('due_date', 'date')
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending']) // pending, partial, paid, overdue, waived
            ->addColumn('notes', 'text', ['null' => true])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->addForeignKey('student_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('fee_type_id', 'fee_types', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['student_id', 'academic_year'])
            ->addIndex(['status'])
            ->create();

        // Fee payments table
        $feePayments = $this->table('fee_payments');
        $feePayments
            ->addColumn('student_fee_id', 'integer')
            ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('payment_date', 'date')
            ->addColumn('payment_method', 'string', ['limit' => 50]) // cash, bank_transfer, card, cheque
            ->addColumn('reference_number', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('receipt_number', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('notes', 'text', ['null' => true])
            ->addColumn('received_by', 'integer', ['null' => true])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->addForeignKey('student_fee_id', 'student_fees', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('received_by', 'users', 'id', ['delete' => 'SET_NULL'])
            ->addIndex(['payment_date'])
            ->addIndex(['receipt_number'], ['unique' => true])
            ->create();
    }
}
