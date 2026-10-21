<?php if ($msg = session('success')): ?>
    <div class="alert alert-success" role="status">
        <span class="alert-icon"><?= icon('check-circle') ?></span>
        <div class="alert-body">Saved successfully<p><?= esc($msg) ?></p></div>
        <button type="button" class="alert-close" data-dismiss aria-label="Dismiss"><?= icon('x', 'icon-sm') ?></button>
    </div>
<?php endif ?>

<?php if ($msg = session('error')): ?>
    <div class="alert alert-danger" role="alert">
        <span class="alert-icon"><?= icon('alert') ?></span>
        <div class="alert-body">Something went wrong<p><?= esc($msg) ?></p></div>
        <button type="button" class="alert-close" data-dismiss aria-label="Dismiss"><?= icon('x', 'icon-sm') ?></button>
    </div>
<?php endif ?>
