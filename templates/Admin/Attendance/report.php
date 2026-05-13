<?php
/**
 * @var \App\View\AppView $this
 * @var array $classesList
 * @var int|null $selectedClassId
 * @var string $selectedMonth
 * @var \App\Model\Entity\SchoolClass|null $selectedClass
 * @var array $report
 * @var array $months
 */
$this->assign('title', __('Attendance Report'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('Attendance Report') ?></h1>
            <p class="text-muted"><?= __('View monthly attendance summaries by class') ?></p>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('Mark Attendance'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
        </div>
    </header>

    <div class="attendance-filters">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
            <div class="filters-row">
                <div class="filter-group">
                    <label><?= __('Select Class') ?></label>
                    <?= $this->Form->control('class_id', [
                        'label' => false,
                        'options' => $classesList,
                        'empty' => __('-- Select Class --'),
                        'value' => $selectedClassId,
                        'class' => 'form-control',
                        'templates' => ['inputContainer' => '{{content}}'],
                    ]) ?>
                </div>
                <div class="filter-group">
                    <label><?= __('Month') ?></label>
                    <?= $this->Form->control('month', [
                        'label' => false,
                        'options' => $months,
                        'value' => $selectedMonth,
                        'class' => 'form-control',
                        'templates' => ['inputContainer' => '{{content}}'],
                    ]) ?>
                </div>
                <button type="submit" class="btn btn--solid"><?= __('Generate Report') ?></button>
            </div>
        <?= $this->Form->end() ?>
    </div>

    <?php if ($selectedClassId && !empty($report)): ?>
        <div class="report-card">
            <div class="report-card__header">
                <h3><?= h($selectedClass->name) ?> <?= $selectedClass->section ? '- ' . h($selectedClass->section) : '' ?></h3>
                <span class="report-period"><?= date('F Y', strtotime($selectedMonth . '-01')) ?></span>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('Student Name') ?></th>
                        <th class="text-center"><?= __('Present') ?></th>
                        <th class="text-center"><?= __('Absent') ?></th>
                        <th class="text-center"><?= __('Late') ?></th>
                        <th class="text-center"><?= __('Excused') ?></th>
                        <th class="text-center"><?= __('Total') ?></th>
                        <th class="text-center"><?= __('Attendance %') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $index = 1; ?>
                    <?php foreach ($report as $row): ?>
                        <tr>
                            <td><?= $row['roll_number'] ?: $index ?></td>
                            <td>
                                <div class="student-info">
                                    <?php if ($row['student']->avatar): ?>
                                        <img src="<?= $this->Url->image($row['student']->avatar) ?>" alt="" class="student-avatar-small">
                                    <?php else: ?>
                                        <span class="student-avatar-initial-small"><?= strtoupper(substr($row['student']->name, 0, 1)) ?></span>
                                    <?php endif; ?>
                                    <span><?= h($row['student']->name) ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="stat-badge stat-badge--success"><?= $row['summary']['present'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="stat-badge stat-badge--danger"><?= $row['summary']['absent'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="stat-badge stat-badge--warning"><?= $row['summary']['late'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="stat-badge stat-badge--info"><?= $row['summary']['excused'] ?></span>
                            </td>
                            <td class="text-center">
                                <strong><?= $row['summary']['total'] ?></strong>
                            </td>
                            <td class="text-center">
                                <?php
                                $percentage = $row['summary']['percentage'];
                                $percentClass = $percentage >= 90 ? 'success' : ($percentage >= 75 ? 'warning' : 'danger');
                                ?>
                                <span class="percentage-badge percentage-badge--<?= $percentClass ?>">
                                    <?= $percentage ?>%
                                </span>
                            </td>
                            <td>
                                <?= $this->Html->link(
                                    __('Details'),
                                    ['action' => 'studentReport', $row['student']->id],
                                    ['class' => 'action-btn action-btn--view']
                                ) ?>
                            </td>
                        </tr>
                        <?php $index++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($selectedClassId): ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128203;</div>
            <h3><?= __('No attendance data') ?></h3>
            <p><?= __('No attendance records found for this class and month.') ?></p>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128200;</div>
            <h3><?= __('Select a class') ?></h3>
            <p><?= __('Choose a class and month above to view the attendance report.') ?></p>
        </div>
    <?php endif; ?>
</section>
