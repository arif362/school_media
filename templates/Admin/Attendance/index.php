<?php
/**
 * @var \App\View\AppView $this
 * @var array $classesList
 * @var iterable $students
 * @var array $attendance
 * @var string $selectedDate
 * @var int|null $selectedClassId
 * @var \App\Model\Entity\SchoolClass|null $selectedClass
 * @var array $statuses
 */
use App\Model\Entity\Attendance;

$this->assign('title', __('Mark Attendance'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('Mark Attendance') ?></h1>
            <p class="text-muted"><?= __('Record daily student attendance for classes') ?></p>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('Attendance Report'), ['action' => 'report'], ['class' => 'btn btn--ghost-dark']) ?>
        </div>
    </header>

    <div class="attendance-filters">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
            <div class="filters-row">
                <div class="filter-group">
                    <label><?= __('Select Class') ?></label>
                    <?= $this->Form->control('class_id', [
                        'label' => false,
                        'options' => $classesList,
                        'empty' => __('-- Select Class --'),
                        'value' => $selectedClassId,
                        'class' => 'form-control',
                        'templates' => ['inputContainer' => '{{content}}'],
                    ]) ?>
                </div>
                <div class="filter-group">
                    <label><?= __('Date') ?></label>
                    <?= $this->Form->control('date', [
                        'label' => false,
                        'type' => 'date',
                        'value' => $selectedDate,
                        'class' => 'form-control',
                        'templates' => ['inputContainer' => '{{content}}'],
                    ]) ?>
                </div>
                <button type="submit" class="btn btn--solid"><?= __('Load Students') ?></button>
            </div>
        <?= $this->Form->end() ?>
    </div>

    <?php if ($selectedClassId && !$students->isEmpty()): ?>
        <div class="attendance-card">
            <div class="attendance-card__header">
                <h3><?= h($selectedClass->name) ?> <?= $selectedClass->section ? '- ' . h($selectedClass->section) : '' ?></h3>
                <span class="attendance-date"><?= date('l, F j, Y', strtotime($selectedDate)) ?></span>
            </div>

            <?= $this->Form->create(null, ['url' => ['action' => 'mark'], 'class' => 'attendance-form']) ?>
                <?= $this->Form->hidden('class_id', ['value' => $selectedClassId]) ?>
                <?= $this->Form->hidden('date', ['value' => $selectedDate]) ?>

                <div class="attendance-quick-actions">
                    <button type="button" class="btn btn--small btn--success" onclick="markAll('present')"><?= __('All Present') ?></button>
                    <button type="button" class="btn btn--small btn--danger" onclick="markAll('absent')"><?= __('All Absent') ?></button>
                    <button type="button" class="btn btn--small btn--ghost-dark" onclick="clearAll()"><?= __('Clear All') ?></button>
                </div>

                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th class="col-roll">#</th>
                            <th class="col-student"><?= __('Student') ?></th>
                            <th class="col-status"><?= __('Status') ?></th>
                            <th class="col-time"><?= __('Check-in Time') ?></th>
                            <th class="col-remarks"><?= __('Remarks') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1; ?>
                        <?php foreach ($students as $studentClass): ?>
                            <?php
                            $studentId = $studentClass->student->id;
                            $existingAttendance = $attendance[$studentId] ?? null;
                            $currentStatus = $existingAttendance?->status ?? '';
                            ?>
                            <tr class="attendance-row" data-student-id="<?= $studentId ?>">
                                <td class="col-roll"><?= $studentClass->roll_number ?: $index ?></td>
                                <td class="col-student">
                                    <div class="student-info">
                                        <?php if ($studentClass->student->avatar): ?>
                                            <img src="<?= $this->Url->image($studentClass->student->avatar) ?>" alt="" class="student-avatar-small">
                                        <?php else: ?>
                                            <span class="student-avatar-initial-small"><?= strtoupper(substr($studentClass->student->name, 0, 1)) ?></span>
                                        <?php endif; ?>
                                        <span><?= h($studentClass->student->name) ?></span>
                                    </div>
                                </td>
                                <td class="col-status">
                                    <div class="status-buttons">
                                        <?php foreach ($statuses as $value => $label): ?>
                                            <?php $statusColor = Attendance::getStatusColors()[$value]; ?>
                                            <label class="status-btn status-btn--<?= $statusColor ?>">
                                                <input type="radio"
                                                       name="attendance[<?= $studentId ?>][status]"
                                                       value="<?= $value ?>"
                                                       <?= $currentStatus === $value ? 'checked' : '' ?>>
                                                <span><?= $label ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td class="col-time">
                                    <?= $this->Form->control("attendance.{$studentId}.check_in_time", [
                                        'label' => false,
                                        'type' => 'time',
                                        'value' => $existingAttendance?->check_in_time?->format('H:i') ?? '',
                                        'class' => 'form-control form-control--small',
                                        'templates' => ['inputContainer' => '{{content}}'],
                                    ]) ?>
                                </td>
                                <td class="col-remarks">
                                    <?= $this->Form->control("attendance.{$studentId}.remarks", [
                                        'label' => false,
                                        'type' => 'text',
                                        'value' => $existingAttendance?->remarks ?? '',
                                        'placeholder' => __('Optional'),
                                        'class' => 'form-control form-control--small',
                                        'templates' => ['inputContainer' => '{{content}}'],
                                    ]) ?>
                                </td>
                            </tr>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="attendance-form__actions">
                    <?= $this->Form->button(__('Save Attendance'), ['class' => 'btn btn--solid btn--large']) ?>
                </div>
            <?= $this->Form->end() ?>
        </div>

        <script>
        function markAll(status) {
            document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(radio => {
                radio.checked = true;
            });
        }

        function clearAll() {
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.checked = false;
            });
        }
        </script>
    <?php elseif ($selectedClassId): ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128100;</div>
            <h3><?= __('No students enrolled') ?></h3>
            <p><?= __('This class has no enrolled students yet.') ?></p>
            <?= $this->Html->link(__('Enroll Students'), ['controller' => 'Classes', 'action' => 'enrollStudent', $selectedClassId], ['class' => 'btn btn--solid']) ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128203;</div>
            <h3><?= __('Select a class') ?></h3>
            <p><?= __('Choose a class and date above to mark attendance.') ?></p>
        </div>
    <?php endif; ?>
</section>
