<?php
/**
 * @var \App\View\AppView $this
 * @var iterable $subjects
 * @var array $categories
 * @var string|null $category
 */
$this->assign('title', __('Subjects'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('Subjects') ?></h1>
            <p class="text-muted"><?= __('Manage curriculum subjects for all grade levels') ?></p>
        </div>
        <div class="header-actions">
            <?= $this->Form->postLink(
                __('Seed Cambridge Subjects'),
                ['action' => 'seedCambridge'],
                ['class' => 'btn btn--ghost-dark', 'confirm' => __('Add all Cambridge curriculum subjects?')]
            ) ?>
            <?= $this->Html->link(__('+ Add Subject'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
        </div>
    </header>

    <div class="filters-bar">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
        <div class="filters-row">
            <div class="filter-group">
                <label><?= __('Category') ?></label>
                <?= $this->Form->control('category', [
                    'type' => 'select',
                    'options' => $categories,
                    'empty' => __('All Categories'),
                    'value' => $category,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <button type="submit" class="btn btn--solid"><?= __('Filter') ?></button>
            <?= $this->Html->link(__('Reset'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <?php if ($subjects->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128218;</div>
            <h3><?= __('No subjects found') ?></h3>
            <p><?= __('Start by adding subjects or seed the Cambridge curriculum.') ?></p>
            <?= $this->Html->link(__('Add Subject'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
        </div>
    <?php else: ?>
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= __('Code') ?></th>
                        <th><?= __('Subject Name') ?></th>
                        <th><?= __('Category') ?></th>
                        <th><?= __('Credit Hours') ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td>
                                <span class="subject-code"><?= h($subject->code) ?></span>
                            </td>
                            <td>
                                <strong><?= h($subject->name) ?></strong>
                            </td>
                            <td>
                                <?php
                                $categoryClass = match ($subject->category) {
                                    'Core' => 'badge--primary',
                                    'Elective' => 'badge--info',
                                    'Co-curricular' => 'badge--success',
                                    default => 'badge--secondary',
                                };
                                ?>
                                <span class="badge <?= $categoryClass ?>"><?= h($subject->category) ?></span>
                            </td>
                            <td><?= $subject->credit_hours ?></td>
                            <td>
                                <span class="status-badge <?= $subject->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                                    <?= $subject->is_active ? __('Active') : __('Inactive') ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?= $this->Html->link(__('View'), ['action' => 'view', $subject->id], ['class' => 'action-btn action-btn--view']) ?>
                                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $subject->id], ['class' => 'action-btn action-btn--edit']) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $subject->id], [
                                        'class' => 'action-btn action-btn--delete',
                                        'confirm' => __('Are you sure you want to delete {0}?', $subject->name),
                                    ]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}')) ?>
            <div class="pagination">
                <?= $this->Paginator->prev(__('Previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('Next')) ?>
            </div>
        </div>
    <?php endif; ?>
</section>
