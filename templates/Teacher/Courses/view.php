<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course $course
 * @var \Cake\Collection\CollectionInterface $enrollments
 * @var int $enrolledCount
 */
$this->assign('title', $course->display_name);
$this->assign('dashboardTitle', $course->display_name);
$this->assign('dashboardSubtitle', h($course->academic_year) . ($course->term ? ' - ' . h($course->term) : ''));
?>

<?php $this->start('dashboardActions'); ?>
    <?= $this->Html->link(__('Update Grades'), ['action' => 'updateGrades', $course->id], ['class' => 'btn btn--solid']) ?>
    <?= $this->Html->link(__('Materials'), ['action' => 'materials', $course->id], ['class' => 'btn btn--outline']) ?>
<?php $this->end(); ?>

<section class="teacher-course-view">
    <div class="stats-grid stats-grid--4">
        <div class="stat-card">
            <span class="stat-card__icon">&#128218;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= h($course->subject->name ?? '-') ?></span>
                <span class="stat-card__label"><?= __('Subject') ?></span>
            </div>
        </div>
        <div class="stat-card">
            <span class="stat-card__icon">&#127979;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= h($course->class->name ?? '-') ?><?= $course->class && $course->class->section ? ' - ' . h($course->class->section) : '' ?></span>
                <span class="stat-card__label"><?= __('Class') ?></span>
            </div>
        </div>
        <div class="stat-card">
            <span class="stat-card__icon">&#128101;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $enrolledCount ?> / <?= $course->max_students ?? '40' ?></span>
                <span class="stat-card__label"><?= __('Enrolled') ?></span>
            </div>
        </div>
        <div class="stat-card">
            <span class="stat-card__icon">&#128196;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= count($course->course_materials ?? []) ?></span>
                <span class="stat-card__label"><?= __('Materials') ?></span>
            </div>
        </div>
    </div>

    <?php if ($course->syllabus): ?>
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Syllabus') ?></h3>
            </div>
            <div class="admin-card__body">
                <div class="syllabus-content">
                    <?= nl2br(h($course->syllabus)) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="admin-card mt-4">
        <div class="admin-card__header">
            <h3><?= __('Enrolled Students') ?> (<?= $enrollments->count() ?>)</h3>
            <?= $this->Html->link(__('Update Grades'), ['action' => 'updateGrades', $course->id], ['class' => 'btn btn--sm btn--solid']) ?>
        </div>
        <div class="admin-card__body">
            <?php if ($enrollments->count() > 0): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><?= __('Student') ?></th>
                                <th><?= __('Enrolled Date') ?></th>
                                <th><?= __('Status') ?></th>
                                <th><?= __('Grade') ?></th>
                                <th><?= __('Marks') ?></th>
                                <th><?= __('Remarks') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $enrollment): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <span class="user-avatar user-avatar--sm"><?= strtoupper(substr($enrollment->student->name ?? 'S', 0, 1)) ?></span>
                                            <div>
                                                <span><?= h($enrollment->student->name ?? __('Unknown')) ?></span>
                                                <br><small class="text-muted"><?= h($enrollment->student->email ?? '') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= $enrollment->enrolled_date->format('M j, Y') ?></td>
                                    <td>
                                        <?php
                                        $statusClass = match($enrollment->status) {
                                            'enrolled' => 'badge--info',
                                            'completed' => 'badge--success',
                                            'dropped' => 'badge--warning',
                                            'failed' => 'badge--danger',
                                            default => 'badge--secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= h(ucfirst($enrollment->status)) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($enrollment->grade): ?>
                                            <span class="grade-badge grade-badge--<?= strtolower(str_replace('*', 'star', $enrollment->grade)) ?>"><?= h($enrollment->grade) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $enrollment->marks !== null ? number_format($enrollment->marks, 2) . '%' : '-' ?></td>
                                    <td><?= h($enrollment->remarks ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p><?= __('No students enrolled in this course yet.') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($course->course_materials)): ?>
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Course Materials') ?></h3>
                <?= $this->Html->link(__('Manage Materials'), ['action' => 'materials', $course->id], ['class' => 'btn btn--sm btn--outline']) ?>
            </div>
            <div class="admin-card__body">
                <div class="materials-list">
                    <?php foreach ($course->course_materials as $material): ?>
                        <div class="material-item">
                            <span class="material-icon">
                                <?php
                                echo match($material->type) {
                                    'document' => '&#128196;',
                                    'video' => '&#127909;',
                                    'link' => '&#128279;',
                                    'assignment' => '&#128221;',
                                    'notes' => '&#128203;',
                                    default => '&#128196;'
                                };
                                ?>
                            </span>
                            <div class="material-info">
                                <strong><?= h($material->title) ?></strong>
                                <?php if ($material->description): ?>
                                    <p class="text-muted"><?= h($material->description) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="material-actions">
                                <?php if ($material->file_path): ?>
                                    <?= $this->Html->link(__('Download'), '/uploads/' . $material->file_path, ['class' => 'btn btn--sm btn--ghost', 'target' => '_blank']) ?>
                                <?php elseif ($material->external_url): ?>
                                    <?= $this->Html->link(__('Open'), $material->external_url, ['class' => 'btn btn--sm btn--ghost', 'target' => '_blank']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
