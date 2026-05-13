<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course $course
 * @var \Cake\Collection\CollectionInterface $enrollments
 * @var array $grades
 * @var array $statuses
 */
$this->assign('title', __('Update Grades'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Courses'), ['action' => 'index']) ?> /
                <?= $this->Html->link($course->display_name, ['action' => 'view', $course->id]) ?> /
                <?= __('Update Grades') ?>
            </nav>
            <h1><?= __('Update Student Grades') ?></h1>
            <p class="text-muted"><?= h($course->display_name) ?> - <?= h($course->academic_year) ?></p>
        </div>
    </header>

    <div class="form-card">
        <?php if ($enrollments->count() > 0): ?>
            <?= $this->Form->create(null, ['class' => 'admin-form']) ?>

            <div class="grades-info">
                <p><?= __('Enter grades and marks for enrolled students. Cambridge grading scale: A* (Highest) to U (Ungraded).') ?></p>
            </div>

            <div class="table-responsive">
                <table class="admin-table grades-table">
                    <thead>
                        <tr>
                            <th><?= __('Student') ?></th>
                            <th><?= __('Enrolled Date') ?></th>
                            <th style="width: 120px;"><?= __('Grade') ?></th>
                            <th style="width: 100px;"><?= __('Marks') ?></th>
                            <th style="width: 130px;"><?= __('Status') ?></th>
                            <th><?= __('Remarks') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enrollments as $enrollment): ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <span class="user-avatar user-avatar--sm"><?= strtoupper(substr($enrollment->student->name ?? 'S', 0, 1)) ?></span>
                                        <span><?= h($enrollment->student->name ?? __('Unknown')) ?></span>
                                    </div>
                                </td>
                                <td><?= $enrollment->enrolled_date->format('M j, Y') ?></td>
                                <td>
                                    <select name="grades[<?= $enrollment->id ?>][grade]" class="form-control form-control--sm">
                                        <option value=""><?= __('--') ?></option>
                                        <?php foreach ($grades as $value => $label): ?>
                                            <option value="<?= h($value) ?>" <?= $enrollment->grade === $value ? 'selected' : '' ?>>
                                                <?= h($value) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="grades[<?= $enrollment->id ?>][marks]"
                                           value="<?= h($enrollment->marks) ?>"
                                           class="form-control form-control--sm"
                                           step="0.01" min="0" max="100"
                                           placeholder="0-100">
                                </td>
                                <td>
                                    <select name="grades[<?= $enrollment->id ?>][status]" class="form-control form-control--sm">
                                        <?php foreach ($statuses as $value => $label): ?>
                                            <option value="<?= h($value) ?>" <?= $enrollment->status === $value ? 'selected' : '' ?>>
                                                <?= h($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="grades[<?= $enrollment->id ?>][remarks]"
                                           value="<?= h($enrollment->remarks) ?>"
                                           class="form-control form-control--sm"
                                           placeholder="<?= __('Optional remarks...') ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="form-actions">
                <?= $this->Html->link(__('Cancel'), ['action' => 'view', $course->id], ['class' => 'btn btn--ghost-dark']) ?>
                <?= $this->Form->button(__('Save All Grades'), ['class' => 'btn btn--solid']) ?>
            </div>

            <?= $this->Form->end() ?>
        <?php else: ?>
            <div class="empty-state">
                <span class="empty-state__icon">&#128101;</span>
                <h3><?= __('No Enrolled Students') ?></h3>
                <p><?= __('There are no students enrolled in this course yet. Enroll students first before updating grades.') ?></p>
                <?= $this->Html->link(__('Enroll Students'), ['action' => 'enrollStudents', $course->id], ['class' => 'btn btn--solid']) ?>
            </div>
        <?php endif; ?>
    </div>
</section>
