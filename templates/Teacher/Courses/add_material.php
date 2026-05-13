<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course $course
 * @var \App\Model\Entity\CourseMaterial $material
 * @var array $types
 */
$this->assign('title', __('Add Material'));
$this->assign('dashboardTitle', __('Add Course Material'));
$this->assign('dashboardSubtitle', h($course->display_name));
?>

<?php $this->start('breadcrumbs'); ?>
    <?= $this->Html->link(__('My Courses'), ['action' => 'index']) ?> /
    <?= $this->Html->link($course->display_name, ['action' => 'view', $course->id]) ?> /
    <?= $this->Html->link(__('Materials'), ['action' => 'materials', $course->id]) ?> /
    <?= __('Add') ?>
<?php $this->end(); ?>

<section class="teacher-add-material">
    <div class="form-card">
        <?= $this->Form->create($material, ['class' => 'admin-form', 'enctype' => 'multipart/form-data']) ?>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('title', [
                    'label' => __('Title'),
                    'class' => 'form-control',
                    'required' => true,
                    'placeholder' => __('Enter material title...'),
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('type', [
                    'type' => 'select',
                    'options' => $types,
                    'label' => __('Type'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-group--full">
                <?= $this->Form->control('description', [
                    'type' => 'textarea',
                    'label' => __('Description'),
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => __('Optional description...'),
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('file', [
                    'type' => 'file',
                    'label' => __('Upload File'),
                    'class' => 'form-control',
                ]) ?>
                <small class="form-hint"><?= __('Upload a document, PDF, video, or other file.') ?></small>
            </div>
            <div class="form-group">
                <?= $this->Form->control('external_url', [
                    'type' => 'url',
                    'label' => __('External URL'),
                    'class' => 'form-control',
                    'placeholder' => 'https://',
                ]) ?>
                <small class="form-hint"><?= __('Or provide a link to external content.') ?></small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('order_num', [
                    'type' => 'number',
                    'label' => __('Display Order'),
                    'class' => 'form-control',
                    'default' => 0,
                    'min' => 0,
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('is_visible', [
                    'type' => 'checkbox',
                    'label' => __('Visible to Students'),
                    'default' => true,
                ]) ?>
            </div>
        </div>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'materials', $course->id], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Add Material'), ['class' => 'btn btn--solid']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>
