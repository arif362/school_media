<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subject $subject
 * @var array $categories
 */
$this->assign('title', __('Edit Subject'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Subjects'), ['action' => 'index']) ?> / <?= __('Edit') ?>
            </nav>
            <h1><?= __('Edit Subject') ?>: <?= h($subject->name) ?></h1>
        </div>
    </header>

    <div class="form-card">
        <?= $this->Form->create($subject, ['class' => 'admin-form']) ?>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('name', [
                    'label' => __('Subject Name'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('code', [
                    'label' => __('Subject Code'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('category', [
                    'type' => 'select',
                    'options' => $categories,
                    'empty' => __('-- Select Category --'),
                    'label' => __('Category'),
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('credit_hours', [
                    'type' => 'number',
                    'label' => __('Credit Hours'),
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 10,
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
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('is_active', [
                    'type' => 'checkbox',
                    'label' => __('Active'),
                ]) ?>
            </div>
        </div>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Update Subject'), ['class' => 'btn btn--solid']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>
