<?php
/**
 * @var \App\View\AppView $this
 * @var array $classes
 * @var array $importErrors
 */
$this->assign('title', __('Import Students'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Students'), ['action' => 'students']) ?> / <?= __('Import') ?>
            </nav>
            <h1><?= __('Bulk Import Students') ?></h1>
            <p class="text-muted"><?= __('Upload a CSV file to import multiple students at once') ?></p>
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
                    <?= $this->Html->link(__('Cancel'), ['action' => 'students'], ['class' => 'btn btn--ghost-dark']) ?>
                    <?= $this->Form->button(__('Import Students'), ['class' => 'btn btn--solid']) ?>
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
                            <td><?= __('Student\'s full name') ?></td>
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
                            <td><code>date_of_birth</code></td>
                            <td><span class="badge badge--secondary"><?= __('No') ?></span></td>
                            <td><?= __('Date in YYYY-MM-DD format') ?></td>
                        </tr>
                        <tr>
                            <td><code>grade_level</code></td>
                            <td><span class="badge badge--secondary"><?= __('No') ?></span></td>
                            <td><?= __('Grade level (e.g., Grade 5)') ?></td>
                        </tr>
                        <tr>
                            <td><code>class_id</code></td>
                            <td><span class="badge badge--secondary"><?= __('No') ?></span></td>
                            <td><?= __('Class ID for assignment') ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-3">
                    <h4><?= __('Example CSV') ?></h4>
                    <pre class="code-block">name,email,password,phone,date_of_birth,grade_level,class_id
John Smith,john@school.edu,,+1234567890,2010-05-15,Grade 5,1
Jane Doe,jane@school.edu,SecurePass123,+1234567891,2010-08-22,Grade 5,1</pre>
                </div>

                <div class="mt-3">
                    <h4><?= __('Available Class IDs') ?></h4>
                    <div class="class-id-list">
                        <?php foreach ($classes as $id => $name): ?>
                            <span class="badge badge--info"><?= $id ?>: <?= h($name) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
