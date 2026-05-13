<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course $course
 * @var \Cake\Collection\CollectionInterface $availableStudents
 */
$this->assign('title', __('Enroll Students'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Courses'), ['action' => 'index']) ?> /
                <?= $this->Html->link($course->display_name, ['action' => 'view', $course->id]) ?> /
                <?= __('Enroll Students') ?>
            </nav>
            <h1><?= __('Enroll Students') ?></h1>
            <p class="text-muted"><?= h($course->display_name) ?> - <?= h($course->class->name ?? '') ?></p>
        </div>
    </header>

    <div class="form-card">
        <?php if ($availableStudents->count() > 0): ?>
            <?= $this->Form->create(null, ['class' => 'admin-form']) ?>

            <div class="enroll-info">
                <p><?= __('Select students from {0} to enroll in this course:', h($course->class->name ?? __('the class'))) ?></p>
            </div>

            <div class="student-selection">
                <div class="selection-header">
                    <label class="checkbox-label">
                        <input type="checkbox" id="selectAll" class="form-checkbox">
                        <span><?= __('Select All') ?></span>
                    </label>
                    <span class="selection-count" id="selectionCount">0 <?= __('selected') ?></span>
                </div>

                <div class="student-grid">
                    <?php foreach ($availableStudents as $studentClass): ?>
                        <label class="student-card">
                            <input type="checkbox" name="student_ids[]" value="<?= $studentClass->student_id ?>" class="student-checkbox">
                            <div class="student-card__content">
                                <span class="user-avatar"><?= strtoupper(substr($studentClass->student->name ?? 'S', 0, 1)) ?></span>
                                <div class="student-card__info">
                                    <strong><?= h($studentClass->student->name ?? __('Unknown')) ?></strong>
                                    <small class="text-muted"><?= h($studentClass->student->email ?? '') ?></small>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-actions">
                <?= $this->Html->link(__('Cancel'), ['action' => 'view', $course->id], ['class' => 'btn btn--ghost-dark']) ?>
                <?= $this->Form->button(__('Enroll Selected Students'), ['class' => 'btn btn--solid']) ?>
            </div>

            <?= $this->Form->end() ?>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const selectAll = document.getElementById('selectAll');
                    const checkboxes = document.querySelectorAll('.student-checkbox');
                    const countDisplay = document.getElementById('selectionCount');

                    function updateCount() {
                        const count = document.querySelectorAll('.student-checkbox:checked').length;
                        countDisplay.textContent = count + ' <?= __('selected') ?>';
                    }

                    selectAll.addEventListener('change', function() {
                        checkboxes.forEach(cb => cb.checked = this.checked);
                        updateCount();
                    });

                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', updateCount);
                    });
                });
            </script>
        <?php else: ?>
            <div class="empty-state">
                <span class="empty-state__icon">&#128101;</span>
                <h3><?= __('No Available Students') ?></h3>
                <p><?= __('All students from this class are already enrolled in this course, or there are no students assigned to this class yet.') ?></p>
                <?= $this->Html->link(__('Back to Course'), ['action' => 'view', $course->id], ['class' => 'btn btn--solid']) ?>
            </div>
        <?php endif; ?>
    </div>
</section>
