<?php $errors = validation_errors(); ?>
<?php if ($errors !== []): ?>
    <div class="error-summary" role="alert">
        <?= icon('alert', 'icon-lg') ?>
        <div>
            <strong>Please fix <?= count($errors) === 1 ? '1 field' : count($errors) . ' fields' ?> below</strong>
            <span>Nothing was saved. Your entries have been kept so you only need to correct the highlighted fields.</span>
        </div>
    </div>
<?php endif ?>
