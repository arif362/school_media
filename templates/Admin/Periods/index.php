<?php
/**
 * Periods Index Template
 *
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Period> $periods
 * @var array $academicYears
 * @var string|null $academicYear
 *
 * @created 2026-05-15
 * @author Arif
 */
$this->assign('title', __('Periods'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('Periods') ?></h1>
            <p class="text-muted"><?= __('Manage daily time slots for class routines') ?></p>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('+ Add Period'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
        </div>
    </header>

    <div class="filters-bar">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
        <div class="filters-row">
            <div class="filter-group">
                <label><?= __('Academic Year') ?></label>
                <?= $this->Form->control('academic_year', [
                    'type' => 'select',
                    'options' => $academicYears,
                    'empty' => __('All Years'),
                    'value' => $academicYear ?? null,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <button type="submit" class="btn btn--solid"><?= __('Filter') ?></button>
            <?= $this->Html->link(__('Reset'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <?php if ($periods->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128337;</div>
            <h3><?= __('No periods found') ?></h3>
            <p><?= __('Start by creating periods (time slots) for your class schedule.') ?></p>
            <?= $this->Html->link(__('Add Period'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
        </div>
    <?php else: ?>
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px"><?= __('Order') ?></th>
                        <th><?= __('Name') ?></th>
                        <th><?= __('Start Time') ?></th>
                        <th><?= __('End Time') ?></th>
                        <th><?= __('Type') ?></th>
                        <th><?= __('Year') ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($periods as $period): ?>
                        <tr>
                            <td>
                                <span class="order-badge"><?= h($period->order_num) ?></span>
                            </td>
                            <td>
                                <strong><?= h($period->name) ?></strong>
                            </td>
                            <td>
                                <?= $period->start_time instanceof \DateTime
                                    ? $period->start_time->format('h:i A')
                                    : h($period->start_time) ?>
                            </td>
                            <td>
                                <?= $period->end_time instanceof \DateTime
                                    ? $period->end_time->format('h:i A')
                                    : h($period->end_time) ?>
                            </td>
                            <td>
                                <?php if ($period->is_break): ?>
                                    <span class="status-badge status-badge--warning"><?= __('Break') ?></span>
                                <?php else: ?>
                                    <span class="status-badge status-badge--info"><?= __('Class') ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= h($period->academic_year) ?></td>
                            <td>
                                <span class="status-badge <?= $period->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                                    <?= $period->is_active ? __('Active') : __('Inactive') ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $period->id], ['class' => 'action-btn action-btn--edit']) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $period->id], [
                                        'class' => 'action-btn action-btn--delete',
                                        'confirm' => __('Are you sure you want to delete this period? This will also delete any routine entries using this period.'),
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

<style>
.order-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: var(--gray-100);
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--gray-600);
}
</style>
