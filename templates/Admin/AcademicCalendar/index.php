<?php
/**
 * Academic Calendar Index Template
 *
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\AcademicEvent> $events
 * @var array $eventsByMonth
 * @var array $academicYears
 * @var array $eventTypes
 * @var string|null $academicYear
 * @var string|null $eventType
 * @var string $viewMode
 *
 * @created 2026-05-15
 * @author Arif
 */
use App\Model\Entity\AcademicEvent;

$this->assign('title', __('Academic Calendar'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('Academic Calendar') ?></h1>
            <p class="text-muted"><?= __('Manage term dates, holidays, and exam schedules') ?></p>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('+ Add Event'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
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
                    'value' => $academicYear ?? null,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="filter-group">
                <label><?= __('Event Type') ?></label>
                <?= $this->Form->control('event_type', [
                    'type' => 'select',
                    'options' => $eventTypes,
                    'empty' => __('All Types'),
                    'value' => $eventType ?? null,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="filter-group">
                <label><?= __('View') ?></label>
                <?= $this->Form->control('view', [
                    'type' => 'select',
                    'options' => ['list' => __('List'), 'calendar' => __('Calendar')],
                    'value' => $viewMode,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <button type="submit" class="btn btn--solid"><?= __('Filter') ?></button>
            <?= $this->Html->link(__('Reset'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <?php if ($events->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128197;</div>
            <h3><?= __('No events found') ?></h3>
            <p><?= __('Start by adding term dates, holidays, and exam schedules.') ?></p>
            <?= $this->Html->link(__('Add Event'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
        </div>
    <?php elseif ($viewMode === 'list'): ?>
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= __('Title') ?></th>
                        <th><?= __('Type') ?></th>
                        <th><?= __('Date') ?></th>
                        <th><?= __('Duration') ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td>
                                <strong><?= h($event->title) ?></strong>
                                <?php if ($event->description): ?>
                                    <br><small class="text-muted"><?= h(\Cake\Utility\Text::truncate($event->description, 50)) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="event-type-badge event-type-badge--<?= h($event->event_type_color) ?>">
                                    <?= h($event->event_type_label) ?>
                                </span>
                            </td>
                            <td><?= h($event->date_range) ?></td>
                            <td>
                                <?php if ($event->duration_days > 1): ?>
                                    <?= __n('{0} day', '{0} days', $event->duration_days, $event->duration_days) ?>
                                <?php else: ?>
                                    <?= __('1 day') ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge <?= $event->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                                    <?= $event->is_active ? __('Active') : __('Inactive') ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $event->id], ['class' => 'action-btn action-btn--edit']) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $event->id], [
                                        'class' => 'action-btn action-btn--delete',
                                        'confirm' => __('Are you sure you want to delete this event?'),
                                    ]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <!-- Calendar View -->
        <div class="calendar-grid">
            <?php
            // Group events by month for display
            $currentDate = new \DateTime();
            $months = [];
            for ($i = 0; $i < 12; $i++) {
                $monthDate = (clone $currentDate)->modify("+{$i} months");
                $monthKey = $monthDate->format('Y-m');
                $months[$monthKey] = [
                    'name' => $monthDate->format('F Y'),
                    'events' => $eventsByMonth[$monthKey] ?? [],
                ];
            }
            ?>
            <?php foreach ($months as $monthKey => $monthData): ?>
                <div class="calendar-month-card">
                    <h3 class="calendar-month-card__title"><?= h($monthData['name']) ?></h3>
                    <?php if (empty($monthData['events'])): ?>
                        <p class="calendar-month-card__empty"><?= __('No events') ?></p>
                    <?php else: ?>
                        <ul class="calendar-month-card__events">
                            <?php foreach ($monthData['events'] as $event): ?>
                                <li class="calendar-event calendar-event--<?= h($event->event_type_color) ?>">
                                    <span class="calendar-event__date">
                                        <?= $event->start_date->format('M j') ?>
                                    </span>
                                    <span class="calendar-event__title"><?= h($event->title) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<style>
.event-type-badge {
    display: inline-block;
    padding: 4px 10px;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 12px;
}

.event-type-badge--green {
    background: var(--green-100);
    color: var(--green-700);
}

.event-type-badge--blue {
    background: var(--blue-100);
    color: var(--blue-700);
}

.event-type-badge--amber {
    background: var(--amber-100);
    color: var(--amber-700);
}

.event-type-badge--red {
    background: var(--red-100);
    color: var(--red-700);
}

.event-type-badge--gray {
    background: var(--gray-100);
    color: var(--gray-700);
}

/* Calendar Grid */
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.calendar-month-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 16px;
}

.calendar-month-card__title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0 0 12px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--gray-200);
}

.calendar-month-card__empty {
    color: var(--gray-400);
    font-size: 0.85rem;
    margin: 0;
}

.calendar-month-card__events {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.calendar-event {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 6px;
    font-size: 0.85rem;
}

.calendar-event--green { background: var(--green-50); }
.calendar-event--blue { background: var(--blue-50); }
.calendar-event--amber { background: var(--amber-50); }
.calendar-event--red { background: var(--red-50); }
.calendar-event--gray { background: var(--gray-50); }

.calendar-event__date {
    font-weight: 600;
    font-size: 0.75rem;
    color: var(--gray-600);
    min-width: 45px;
}

.calendar-event__title {
    color: var(--gray-800);
}
</style>
