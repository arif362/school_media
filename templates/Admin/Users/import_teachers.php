<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Collection\CollectionInterface $subjects
 * @var array $importErrors
 */
$this->assign('title', __('Import Teachers'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Teachers'), ['action' => 'teachers']) ?> / <?= __('Import') ?>
            </nav>
            <h1><?= __('Bulk Import Teachers') ?></h1>
            <p class="text-muted"><?= __('Upload a CSV file to import multiple teachers at once') ?></p>
        </div>
    </header>

    <div class="admin-grid admin-grid--2">
        <div class="form-card">
            <div class="form-section">
                <h3 class="form-section__title"><?= __('Upload CSV File') ?></h3>

                <?= $this->Form->create(null, ['class' => 'admin-form', 'enctype' => 'multipart/form-data']) ?>

                <div class="form-group">
                    <?= $this->Form->control('csv_file', [
                        'type' => 'file',
                        'label' => __('CSV File'),
                        'class' => 'form-control',
                        'accept' => '.csv',
                        'required' => true,
                    ]) ?>
                </div>

                <div class="form-actions" style="justify-content: flex-start;">
                    <?= $this->Html->link(__('Cancel'), ['action' => 'teachers'], ['class' => 'btn btn--ghost-dark']) ?>
                    <?= $this->Form->button(__('Import Teachers'), ['class' => 'btn btn--solid']) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>

            <?php if (!empty($importErrors)): ?>
                <div class="form-section">
                    <h3 class="form-section__title text-danger"><?= __('Import Errors') ?></h3>
                    <div class="import-errors">
                        <?php foreach ($importErrors as $error): ?>
                            <p class="text-danger"><?= h($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('CSV Format Guide') ?></h3>
            </div>
            <div class="admin-card__body">
                <p><?= __('Your CSV file should have the following columns in order:') ?></p>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><?= __('Column') ?></th>
                            <th><?= __('Required') ?></th>
                            <th><?= __('Description') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>name</code></td>
                            <td><span class="badge badge--danger"><?= __('Yes') ?></span></td>
                            <td><?= __('Teacher\'s full name') ?></td>
                        </tr>
                        <tr>
                            <td><code>email</code></td>
                            <td><span class="badge badge--danger"><?= __('Yes') ?></span></td>
                            <td><?= __('Unique email address') ?></td>
                        </tr>
                        <tr>
                            <td><code>password</code></td>
                            <td><span class="badge badge--secondary"><?= __('No') ?></span></td>
                            <td><?= __('Password (auto-generated if empty)') ?></td>
                        </tr>
                        <tr>
                            <td><code>phone</code></td>
                            <td><span class="badge badge--secondary"><?= __('No') ?></span></td>
                            <td><?= __('Phone number') ?></td>
                        </tr>
                        <tr>
                            <td><code>subject_ids</code></td>
                            <td><span class="badge badge--secondary"><?= __('No') ?></span></td>
                            <td><?= __('Comma-separated subject IDs') ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-3">
                    <h4><?= __('Example CSV') ?></h4>
                    <pre class="code-block">name,email,password,phone,subject_ids
Sarah Johnson,sarah@school.edu,,+1234567890,"1,2,3"
Michael Brown,michael@school.edu,SecurePass123,+1234567891,"4,5"</pre>
                </div>

                <div class="mt-3">
                    <h4><?= __('Available Subject IDs') ?></h4>
                    <div class="subject-id-list">
                        <?php foreach ($subjects as $subject): ?>
                            <span class="badge badge--info"><?= $subject->id ?>: <?= h($subject->name) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
