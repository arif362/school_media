<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SchoolClass> $classes
 * @var array $years
 * @var string|null $academicYear
 */
$this->assign('title', __('Manage Classes'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('Classes') ?></h1>
            <p class="text-muted"><?= __('Manage school classes and sections') ?></p>
        </div>
        <?= $this->Html->link(__('+ Add Class'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
    </header>

    <div class="admin-filters">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
            <div class="filters-row">
                <div class="filter-group">
                    <?= $this->Form->control('academic_year', [
                        'label' => false,
                        'options' => $years,
                        'empty' => __('All Academic Years'),
                        'value' => $academicYear,
                        'class' => 'form-control',
                        'templates' => ['inputContainer' => '{{content}}'],
                    ]) ?>
                </div>
                <button type="submit" class="btn btn--solid btn--small"><?= __('Filter') ?></button>
                <?= $this->Html->link(__('Clear'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark btn--small']) ?>
            </div>
        <?= $this->Form->end() ?>
    </div>

    <?php if ($classes->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#127979;</div>
            <h3><?= __('No classes found') ?></h3>
            <p><?= __('Create your first class to start managing students.') ?></p>
            <?= $this->Html->link(__('Add Class'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('name', __('Class Name')) ?></th>
                        <th><?= $this->Paginator->sort('grade_level', __('Grade Level')) ?></th>
                        <th><?= __('Section') ?></th>
                        <th><?= __('Class Teacher') ?></th>
                        <th><?= $this->Paginator->sort('academic_year', __('Academic Year')) ?></th>
                        <th><?= __('Status') ?></th>
                        <th class="text-right"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td>
                                <strong><?= $this->Html->link(h($class->name), ['action' => 'view', $class->id]) ?></strong>
                            </td>
                            <td><?= h($class->grade_level) ?></td>
                            <td><?= $class->section ? h($class->section) : '-' ?></td>
                            <td><?= $class->class_teacher ? h($class->class_teacher->name) : '-' ?></td>
                            <td><?= h($class->academic_year) ?></td>
                            <td>
                                <span class="status-badge <?= $class->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                                    <?= $class->is_active ? __('Active') : __('Inactive') ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?= $this->Html->link(__('View'), ['action' => 'view', $class->id], ['class' => 'action-btn action-btn--view']) ?>
                                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $class->id], ['class' => 'action-btn action-btn--edit']) ?>
                                    <?= $this->Form->postLink(
                                        __('Delete'),
                                        ['action' => 'delete', $class->id],
                                        ['confirm' => __('Delete this class?'), 'class' => 'action-btn action-btn--delete']
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            <div class="pagination-summary">
                <?= $this->Paginator->counter(__('Showing {{start}} to {{end}} of {{count}}')) ?>
            </div>
            <nav class="pagination-modern">
                <?= $this->Paginator->prev(__('Prev')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('Next')) ?>
            </nav>
        </div>
    <?php endif; ?>
</section>
