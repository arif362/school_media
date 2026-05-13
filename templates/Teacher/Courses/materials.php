<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course $course
 * @var \Cake\Collection\CollectionInterface $materials
 */
$this->assign('title', __('Course Materials'));
$this->assign('dashboardTitle', __('Course Materials'));
$this->assign('dashboardSubtitle', h($course->display_name));
?>

<?php $this->start('dashboardActions'); ?>
    <?= $this->Html->link(__('Add Material'), ['action' => 'addMaterial', $course->id], ['class' => 'btn btn--solid']) ?>
<?php $this->end(); ?>

<?php $this->start('breadcrumbs'); ?>
    <?= $this->Html->link(__('My Courses'), ['action' => 'index']) ?> /
    <?= $this->Html->link($course->display_name, ['action' => 'view', $course->id]) ?> /
    <?= __('Materials') ?>
<?php $this->end(); ?>

<section class="teacher-materials">
    <?php if ($materials->count() > 0): ?>
        <div class="materials-grid">
            <?php foreach ($materials as $material): ?>
                <div class="material-card">
                    <div class="material-card__header">
                        <span class="material-card__icon">
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
                        <span class="badge badge--<?= $material->is_visible ? 'success' : 'secondary' ?>">
                            <?= $material->is_visible ? __('Visible') : __('Hidden') ?>
                        </span>
                    </div>
                    <div class="material-card__body">
                        <h4><?= h($material->title) ?></h4>
                        <?php if ($material->description): ?>
                            <p class="text-muted"><?= h($material->description) ?></p>
                        <?php endif; ?>
                        <small class="text-muted">
                            <?= __('Type: {0}', h(ucfirst($material->type))) ?>
                            <br>
                            <?= __('Added: {0}', $material->created->format('M j, Y')) ?>
                        </small>
                    </div>
                    <div class="material-card__footer">
                        <?php if ($material->file_path): ?>
                            <?= $this->Html->link(__('Download'), '/uploads/' . $material->file_path, ['class' => 'btn btn--sm btn--outline', 'target' => '_blank']) ?>
                        <?php elseif ($material->external_url): ?>
                            <?= $this->Html->link(__('Open Link'), $material->external_url, ['class' => 'btn btn--sm btn--outline', 'target' => '_blank']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <span class="empty-state__icon">&#128196;</span>
            <h3><?= __('No Materials Yet') ?></h3>
            <p><?= __('Add documents, videos, links, or assignments for your students.') ?></p>
            <?= $this->Html->link(__('Add Material'), ['action' => 'addMaterial', $course->id], ['class' => 'btn btn--solid']) ?>
        </div>
    <?php endif; ?>
</section>
