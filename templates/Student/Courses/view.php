<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\StudentCourse $enrollment
 * @var \Cake\Collection\CollectionInterface $classmates
 */
$course = $enrollment->course;
$this->assign('title', $course->display_name ?? __('Course Details'));
$this->assign('dashboardTitle', $course->display_name ?? __('Course Details'));
$this->assign('dashboardSubtitle', h($course->academic_year) . ($course->term ? ' - ' . h($course->term) : ''));
?>

<?php $this->start('breadcrumbs'); ?>
    <?= $this->Html->link(__('My Courses'), ['action' => 'index']) ?> /
    <?= h($course->subject->name ?? __('Course')) ?>
<?php $this->end(); ?>

<section class="student-course-view">
    <div class="course-header-card">
        <div class="course-header-card__icon">&#128218;</div>
        <div class="course-header-card__content">
            <h2><?= h($course->subject->name ?? __('Unknown Subject')) ?></h2>
            <p class="text-muted"><?= h($course->subject->code ?? '') ?></p>
            <div class="course-header-card__meta">
                <span>
                    <strong><?= __('Class:') ?></strong>
                    <?= h($course->class->name ?? '-') ?><?= $course->class && $course->class->section ? ' - ' . h($course->class->section) : '' ?>
                </span>
                <span>
                    <strong><?= __('Teacher:') ?></strong>
                    <?= h($course->teacher->name ?? __('TBA')) ?>
                </span>
                <span>
                    <strong><?= __('Status:') ?></strong>
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
                </span>
            </div>
        </div>
        <?php if ($enrollment->grade || $enrollment->marks !== null): ?>
            <div class="course-header-card__grade">
                <?php if ($enrollment->grade): ?>
                    <span class="grade-badge grade-badge--xl"><?= h($enrollment->grade) ?></span>
                <?php endif; ?>
                <?php if ($enrollment->marks !== null): ?>
                    <span class="marks-display marks-display--lg"><?= number_format($enrollment->marks, 1) ?>%</span>
                <?php endif; ?>
                <?php if ($enrollment->remarks): ?>
                    <p class="text-muted"><?= h($enrollment->remarks) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="admin-grid admin-grid--2 mt-4">
        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('Course Information') ?></h3>
            </div>
            <div class="admin-card__body">
                <table class="detail-table">
                    <tr>
                        <th><?= __('Subject') ?></th>
                        <td><?= h($course->subject->name ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Subject Code') ?></th>
                        <td><?= h($course->subject->code ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Academic Year') ?></th>
                        <td><?= h($course->academic_year) ?></td>
                    </tr>
                    <?php if ($course->term): ?>
                        <tr>
                            <th><?= __('Term') ?></th>
                            <td><?= h($course->term) ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th><?= __('Enrolled Date') ?></th>
                        <td><?= $enrollment->enrolled_date->format('F j, Y') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('Your Progress') ?></h3>
            </div>
            <div class="admin-card__body">
                <?php if ($enrollment->grade || $enrollment->marks !== null): ?>
                    <div class="progress-display">
                        <?php if ($enrollment->marks !== null): ?>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: <?= min($enrollment->marks, 100) ?>%"></div>
                            </div>
                            <div class="progress-stats">
                                <span class="progress-value"><?= number_format($enrollment->marks, 1) ?>%</span>
                                <?php if ($enrollment->grade): ?>
                                    <span class="progress-grade">Grade: <?= h($enrollment->grade) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php elseif ($enrollment->grade): ?>
                            <div class="grade-only-display">
                                <span class="grade-badge grade-badge--xl"><?= h($enrollment->grade) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="no-grades">
                        <p class="text-muted"><?= __('Grades have not been posted yet.') ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($enrollment->remarks): ?>
                    <div class="teacher-remarks mt-3">
                        <h4><?= __('Teacher Remarks') ?></h4>
                        <p><?= h($enrollment->remarks) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($course->syllabus): ?>
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Course Syllabus') ?></h3>
            </div>
            <div class="admin-card__body">
                <div class="syllabus-content">
                    <?= nl2br(h($course->syllabus)) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($course->course_materials)): ?>
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Course Materials') ?> (<?= count($course->course_materials) ?>)</h3>
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
                                <small class="text-muted">
                                    <?= __('Added: {0}', $material->created->format('M j, Y')) ?>
                                </small>
                            </div>
                            <div class="material-actions">
                                <?php if ($material->file_path): ?>
                                    <?= $this->Html->link(__('Download'), '/uploads/' . $material->file_path, ['class' => 'btn btn--sm btn--solid', 'target' => '_blank']) ?>
                                <?php elseif ($material->external_url): ?>
                                    <?= $this->Html->link(__('Open'), $material->external_url, ['class' => 'btn btn--sm btn--solid', 'target' => '_blank']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($classmates->count() > 0): ?>
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Classmates') ?></h3>
            </div>
            <div class="admin-card__body">
                <div class="classmates-grid">
                    <?php foreach ($classmates as $classmateEnrollment): ?>
                        <div class="classmate-item">
                            <span class="user-avatar user-avatar--sm"><?= strtoupper(substr($classmateEnrollment->student->name ?? 'S', 0, 1)) ?></span>
                            <span><?= h($classmateEnrollment->student->name ?? __('Unknown')) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
