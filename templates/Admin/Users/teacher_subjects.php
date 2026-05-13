<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface $subjects
 * @var array $currentSubjectIds
 * @var int|null $primarySubjectId
 */
$this->assign('title', __('Manage Teacher Subjects'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Teachers'), ['action' => 'teachers']) ?> /
                <?= $this->Html->link(h($user->name), ['action' => 'view', $user->id]) ?> /
                <?= __('Subjects') ?>
            </nav>
            <h1><?= __('Manage Subject Assignments') ?></h1>
            <p class="text-muted"><?= h($user->name) ?></p>
        </div>
    </header>

    <div class="form-card">
        <?= $this->Form->create(null, ['class' => 'admin-form']) ?>

        <div class="form-section">
            <h3 class="form-section__title"><?= __('Assign Subjects') ?></h3>
            <p class="text-muted"><?= __('Select subjects this teacher can teach. Mark one as primary expertise.') ?></p>

            <div class="subject-assignment-grid">
                <?php foreach ($subjects as $subject): ?>
                    <?php $isChecked = in_array($subject->id, $currentSubjectIds); ?>
                    <div class="subject-assignment-card<?= $isChecked ? ' is-selected' : '' ?>">
                        <div class="subject-assignment-card__header">
                            <label class="checkbox-label">
                                <input type="checkbox" name="subject_ids[]" value="<?= $subject->id ?>"
                                       <?= $isChecked ? 'checked' : '' ?>
                                       class="subject-checkbox">
                                <strong><?= h($subject->name) ?></strong>
                            </label>
                        </div>
                        <div class="subject-assignment-card__body">
                            <p class="text-muted">
                                <small><?= h($subject->code) ?></small>
                                <?php if ($subject->category): ?>
                                    <br><span class="badge badge--info"><?= h($subject->category) ?></span>
                                <?php endif; ?>
                            </p>
                            <label class="radio-label">
                                <input type="radio" name="primary_subject_id" value="<?= $subject->id ?>"
                                       <?= $primarySubjectId == $subject->id ? 'checked' : '' ?>>
                                <?= __('Primary Expertise') ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'view', $user->id], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Save Assignments'), ['class' => 'btn btn--solid']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.subject-assignment-card');
    cards.forEach(card => {
        const checkbox = card.querySelector('.subject-checkbox');
        checkbox.addEventListener('change', function() {
            card.classList.toggle('is-selected', this.checked);
        });
    });
});
</script>
