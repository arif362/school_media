<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $student
 * @var array $summary
 * @var array $monthlyTrend
 * @var iterable $recentAttendance
 * @var int $year
 * @var array $years
 */
use App\Model\Entity\Attendance;

$this->assign('title', __('Student Attendance Report'));
$statuses = Attendance::getStatuses();
$statusColors = Attendance::getStatusColors();
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Attendance Report'), ['action' => 'report']) ?> / <?= __('Student Details') ?>
            </nav>
            <h1><?= h($student->name) ?></h1>
            <p class="text-muted"><?= __('Individual attendance report for {0}', $year) ?></p>
        </div>
        <div class="header-actions">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'year-filter']) ?>
                <?= $this->Form->control('year', [
                    'label' => false,
                    'options' => array_combine($years, $years),
                    'value' => $year,
                    'class' => 'form-control',
                    'onchange' => 'this.form.submit()',
                    'templates' => ['inputContainer' => '{{content}}'],
                ]) ?>
            <?= $this->Form->end() ?>
        </div>
    </header>

    <div class="student-attendance-overview">
        <div class="student-profile-card">
            <div class="student-profile-card__avatar">
                <?php if ($student->avatar): ?>
                    <img src="<?= $this->Url->image($student->avatar) ?>" alt="<?= h($student->name) ?>">
                <?php else: ?>
                    <span class="avatar-initial-large"><?= strtoupper(substr($student->name, 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div class="student-profile-card__info">
                <h3><?= h($student->name) ?></h3>
                <p><?= h($student->email) ?></p>
                <?php if ($student->grade_level): ?>
                    <span class="badge badge--primary"><?= h($student->grade_level) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="attendance-stats-grid">
            <div class="attendance-stat attendance-stat--percentage">
                <span class="attendance-stat__value"><?= $summary['percentage'] ?>%</span>
                <span class="attendance-stat__label"><?= __('Attendance Rate') ?></span>
            </div>
            <div class="attendance-stat attendance-stat--success">
                <span class="attendance-stat__value"><?= $summary['present'] ?></span>
                <span class="attendance-stat__label"><?= __('Present') ?></span>
            </div>
            <div class="attendance-stat attendance-stat--danger">
                <span class="attendance-stat__value"><?= $summary['absent'] ?></span>
                <span class="attendance-stat__label"><?= __('Absent') ?></span>
            </div>
            <div class="attendance-stat attendance-stat--warning">
                <span class="attendance-stat__value"><?= $summary['late'] ?></span>
                <span class="attendance-stat__label"><?= __('Late') ?></span>
            </div>
            <div class="attendance-stat attendance-stat--info">
                <span class="attendance-stat__value"><?= $summary['excused'] ?></span>
                <span class="attendance-stat__label"><?= __('Excused') ?></span>
            </div>
        </div>
    </div>

    <div class="attendance-charts-row">
        <div class="chart-card">
            <h3><?= __('Monthly Attendance Trend') ?></h3>
            <div class="monthly-trend">
                <?php
                $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                ?>
                <?php foreach ($monthlyTrend as $month => $data): ?>
                    <div class="trend-bar">
                        <div class="trend-bar__fill" style="height: <?= $data['percentage'] ?>%">
                            <span class="trend-bar__value"><?= $data['percentage'] ?>%</span>
                        </div>
                        <span class="trend-bar__label"><?= $monthNames[$month - 1] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="recent-attendance-card">
        <h3><?= __('Recent Attendance History') ?></h3>
        <?php if (!$recentAttendance->isEmpty()): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= __('Date') ?></th>
                        <th><?= __('Class') ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= __('Check-in') ?></th>
                        <th><?= __('Remarks') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentAttendance as $record): ?>
                        <tr>
                            <td><?= $record->date->format('D, M j, Y') ?></td>
                            <td><?= h($record->class->name) ?></td>
                            <td>
                                <span class="status-badge status-badge--<?= $statusColors[$record->status] ?>">
                                    <?= $statuses[$record->status] ?>
                                </span>
                            </td>
                            <td><?= $record->check_in_time ? $record->check_in_time->format('h:i A') : '-' ?></td>
                            <td><?= $record->remarks ? h($record->remarks) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted text-center"><?= __('No attendance records found.') ?></p>
        <?php endif; ?>
    </div>
</section>
