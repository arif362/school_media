<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface $currentClasses
 * @var array $classes
 * @var array $academicYears
 */
$this->assign('title', __('Manage Student Classes'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Students'), ['action' => 'students']) ?> /
                <?= $this->Html->link(h($user->name), ['action' => 'view', $user->id]) ?> /
                <?= __('Classes') ?>
            </nav>
            <h1><?= __('Manage Class Assignments') ?></h1>
            <p class="text-muted"><?= h($user->name) ?></p>
        </div>
    </header>

    <div class="admin-grid admin-grid--2">
        <!-- Assign New Class -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('Assign to Class') ?></h3>
            </div>
            <div class="admin-card__body">
                <?= $this->Form->create(null, ['class' => 'admin-form']) ?>

                <div class="form-group">
                    <?= $this->Form->control('class_id', [
                        'type' => 'select',
                        'options' => $classes,
                        'empty' => __('-- Select Class --'),
                        'label' => __('Class'),
                        'class' => 'form-control',
                        'required' => true,
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('academic_year', [
                        'type' => 'select',
                        'options' => $academicYears,
                        'label' => __('Academic Year'),
                        'class' => 'form-control',
                        'required' => true,
                    ]) ?>
                </div>

                <div class="form-actions" style="justify-content: flex-start;">
                    <?= $this->Form->button(__('Assign to Class'), ['class' => 'btn btn--solid']) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>

        <!-- Current Assignments -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('Class History') ?></h3>
            </div>
            <div class="admin-card__body">
                <?php if ($currentClasses->count() > 0): ?>
                    <div class="class-history-list">
                        <?php foreach ($currentClasses as $sc): ?>
                            <div class="class-history-item">
                                <div class="class-history-item__info">
                                    <strong>
                                        <?= h($sc->class->name ?? '') ?>
                                        <?= $sc->class && $sc->class->section ? ' - ' . h($sc->class->section) : '' ?>
                                    </strong>
                                    <p class="text-muted">
                                        <?= h($sc->academic_year) ?>
                                        <?php if ($sc->class): ?>
                                            <br><small><?= h($sc->class->grade_level) ?></small>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="class-history-item__status">
                                    <?php
                                    $statusClass = match($sc->status) {
                                        'active' => 'badge--success',
                                        'graduated' => 'badge--info',
                                        'transferred' => 'badge--warning',
                                        default => 'badge--secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= h(ucfirst($sc->status)) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted"><?= __('No class assignments yet.') ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="form-actions mt-4">
        <?= $this->Html->link(__('Back to Student'), ['action' => 'view', $user->id], ['class' => 'btn btn--outline']) ?>
    </div>
</section>
