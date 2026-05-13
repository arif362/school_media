<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\ResultSet $attendance
 * @var array $attendanceSummary
 * @var array $monthlyTrend
 * @var \App\Model\Entity\SchoolClass|null $studentClass
 * @var array $years
 * @var array $months
 * @var int|string $year
 * @var int|string|null $month
 */
$this->assign('title', __('My Attendance'));
$this->assign('dashboardTitle', __('My Attendance'));
$this->assign('dashboardSubtitle', __('View your attendance records and statistics.'));

$monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
?>

<div class="student-attendance">
    <!-- Class Info -->
    <?php if ($studentClass): ?>
        <div class="class-banner">
            <span class="class-banner__name"><?= h($studentClass->name) ?> <?= $studentClass->section ? '(' . h($studentClass->section) . ')' : '' ?></span>
            <span class="class-banner__grade"><?= h($studentClass->grade_level) ?></span>
            <span class="class-banner__year"><?= h($studentClass->academic_year) ?></span>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="filters-bar">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
        <div class="filters-row">
            <?= $this->Form->control('year', [
                'type' => 'select',
                'options' => $years,
                'value' => $year,
                'label' => false,
                'class' => 'form-control form-control--small',
            ]) ?>
            <?= $this->Form->control('month', [
                'type' => 'select',
                'options' => $months,
                'value' => $month,
                'label' => false,
                'class' => 'form-control form-control--small',
            ]) ?>
            <?= $this->Form->button(__('Filter'), ['class' => 'btn btn--small btn--solid']) ?>
            <?= $this->Html->link(__('Reset'), ['action' => 'index'], ['class' => 'btn btn--small btn--ghost-dark']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <!-- Summary Cards -->
    <div class="attendance-summary-cards">
        <div class="summary-card summary-card--main">
            <div class="summary-card__content">
                <span class="summary-card__value <?= $attendanceSummary['percentage'] >= 75 ? 'text-success' : ($attendanceSummary['percentage'] >= 50 ? 'text-warning' : 'text-danger') ?>">
                    <?= $attendanceSummary['percentage'] ?>%
                </span>
                <span class="summary-card__label"><?= __('Attendance Rate') ?></span>
            </div>
            <div class="summary-card__progress">
                <div class="progress-ring">
                    <svg viewBox="0 0 36 36" class="progress-ring__svg">
                        <path class="progress-ring__bg"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="progress-ring__fill <?= $attendanceSummary['percentage'] >= 75 ? 'progress-ring__fill--success' : ($attendanceSummary['percentage'] >= 50 ? 'progress-ring__fill--warning' : 'progress-ring__fill--danger') ?>"
                            stroke-dasharray="<?= $attendanceSummary['percentage'] ?>, 100"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="summary-card summary-card--present">
            <span class="summary-card__value"><?= $attendanceSummary['present'] ?></span>
            <span class="summary-card__label"><?= __('Present') ?></span>
        </div>

        <div class="summary-card summary-card--absent">
            <span class="summary-card__value"><?= $attendanceSummary['absent'] ?></span>
            <span class="summary-card__label"><?= __('Absent') ?></span>
        </div>

        <div class="summary-card summary-card--late">
            <span class="summary-card__value"><?= $attendanceSummary['late'] ?></span>
            <span class="summary-card__label"><?= __('Late') ?></span>
        </div>

        <div class="summary-card summary-card--excused">
            <span class="summary-card__value"><?= $attendanceSummary['excused'] ?></span>
            <span class="summary-card__label"><?= __('Excused') ?></span>
        </div>

        <div class="summary-card summary-card--total">
            <span class="summary-card__value"><?= $attendanceSummary['total'] ?></span>
            <span class="summary-card__label"><?= __('Total Days') ?></span>
        </div>
    </div>

    <?php if ($attendanceSummary['percentage'] < 75 && $attendanceSummary['total'] > 0): ?>
        <div class="alert alert--warning">
            <strong><?= __('Attendance Alert') ?>:</strong>
            <?= __('Your attendance is below 75%. Maintaining good attendance is important for your academic success. Please speak with your class teacher if you have any concerns.') ?>
        </div>
    <?php endif; ?>

    <!-- Monthly Trend Chart -->
    <?php if (!$month): ?>
    <div class="dashboard-card monthly-trend-card">
        <div class="dashboard-card__header">
            <h3><?= __('Monthly Attendance Trend') ?> - <?= $year ?></h3>
        </div>
        <div class="dashboard-card__body">
            <div class="trend-chart">
                <?php foreach ($monthlyTrend as $m => $data): ?>
                    <div class="trend-bar-container">
                        <div class="trend-bar" style="height: <?= $data['total'] > 0 ? $data['percentage'] : 0 ?>%">
                            <?php if ($data['total'] > 0): ?>
                                <span class="trend-bar__value"><?= $data['percentage'] ?>%</span>
                            <?php endif; ?>
                        </div>
                        <span class="trend-bar__label"><?= $monthNames[$m] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Attendance Records Table -->
    <div class="dashboard-card">
        <div class="dashboard-card__header">
            <h3><?= __('Attendance Records') ?></h3>
        </div>
        <div class="dashboard-card__body">
            <?php if ($attendance->isEmpty()): ?>
                <div class="empty-state">
                    <p><?= __('No attendance records found for the selected period.') ?></p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><?= __('Date') ?></th>
                            <th><?= __('Day') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Check In') ?></th>
                            <th><?= __('Check Out') ?></th>
                            <th><?= __('Remarks') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendance as $record): ?>
                            <tr>
                                <td>
                                    <strong><?= $record->date->format('M j, Y') ?></strong>
                                </td>
                                <td><?= $record->date->format('l') ?></td>
                                <td>
                                    <span class="attendance-status attendance-status--<?= $record->status ?>">
                                        <?= h(ucfirst(str_replace('_', ' ', $record->status))) ?>
                                    </span>
                                </td>
                                <td><?= $record->check_in_time ? $record->check_in_time->format('h:i A') : '-' ?></td>
                                <td><?= $record->check_out_time ? $record->check_out_time->format('h:i A') : '-' ?></td>
                                <td><?= $record->remarks ? h($record->remarks) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?>
                    <div class="pagination">
                        <?= $this->Paginator->prev('< ' . __('Previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('Next') . ' >') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
