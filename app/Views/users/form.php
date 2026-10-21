<?php
$isEdit = $user !== null;
// Previous input wins (after a failed submit); otherwise the stored value. Always escaped.
$val     = static fn (string $field) => esc((string) old($field, $user[$field] ?? '', false));
$oldRole = (string) old('role', $user['role'] ?? 'cashier', false);
?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?= view('partials/page_head', [
    'crumbs'   => ['Home' => site_url('/'), 'User Accounts' => site_url('users'), ($isEdit ? 'Edit' : 'New') => null],
    'heading'  => $isEdit ? 'Edit ' . $user['full_name'] : 'New User',
    'subtitle' => $isEdit
        ? 'Update account details, reset the password, or upload a profile picture.'
        : 'Create a staff account that can sign in to the POS. Fields marked * are required.',
    'actions'  => '<a href="' . site_url('users') . '" class="btn btn-ghost">' . icon('arrow-left') . 'Back to users</a>',
]) ?>

<form action="<?= $action ?>" method="post" class="form-layout<?= $isEdit ? '' : ' single' ?>"
      <?= $isEdit ? 'enctype="multipart/form-data"' : '' ?> novalidate>
    <?= csrf_field() ?>

    <div class="card">
        <?= view('partials/error_summary') ?>

        <div class="form-section">
            <h2 class="section-title"><?= icon('user') ?>Account details</h2>
            <p class="section-sub">How this person appears in the system.</p>

            <div class="fields">
                <div class="field">
                    <label for="username">Username<span class="req" aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                        <?= icon('user') ?>
                        <input type="text" id="username" name="username" class="input<?= invalid('username') ?>"
                               value="<?= $val('username') ?>" placeholder="e.g. cashier03" maxlength="50"
                               autocomplete="off" required <?= $isEdit ? '' : 'autofocus' ?>
                               <?= field_error('username') ? 'aria-invalid="true" aria-describedby="username-error"' : '' ?>>
                    </div>
                    <?= error_text('username') ?: '<p class="help">Must be unique. Letters, numbers, dots, _ and - only.</p>' ?>
                </div>

                <div class="field">
                    <label for="full_name">Full name<span class="req" aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                        <?= icon('contact') ?>
                        <input type="text" id="full_name" name="full_name" class="input<?= invalid('full_name') ?>"
                               value="<?= $val('full_name') ?>" placeholder="e.g. Rafael Mendoza" maxlength="120" required
                               <?= field_error('full_name') ? 'aria-invalid="true" aria-describedby="full_name-error"' : '' ?>>
                    </div>
                    <?= error_text('full_name') ?>
                </div>

                <div class="field">
                    <label for="email">Email<span class="opt">Optional</span></label>
                    <div class="input-wrap">
                        <?= icon('mail') ?>
                        <input type="email" id="email" name="email" class="input<?= invalid('email') ?>"
                               value="<?= $val('email') ?>" placeholder="name@pos.local" maxlength="150"
                               <?= field_error('email') ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>>
                    </div>
                    <?= error_text('email') ?>
                </div>

                <div class="field">
                    <label for="role">Role<span class="req" aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                        <?= icon('shield') ?>
                        <select id="role" name="role" class="input<?= invalid('role') ?>" required>
                            <?php foreach ($roles as $key => $label): ?>
                                <option value="<?= esc($key, 'attr') ?>" <?= $oldRole === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?= error_text('role') ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2 class="section-title"><?= icon('lock') ?><?= $isEdit ? 'Change password' : 'Password' ?></h2>
            <p class="section-sub">
                <?= $isEdit ? 'Leave both fields blank to keep the current password.' : 'At least 8 characters. Stored as a secure hash, never as plain text.' ?>
            </p>

            <div class="fields">
                <div class="field">
                    <label for="password"><?= $isEdit ? 'New password' : 'Password' ?><?= $isEdit ? '<span class="opt">Optional</span>' : '<span class="req" aria-hidden="true">*</span>' ?></label>
                    <div class="input-wrap">
                        <?= icon('lock') ?>
                        <input type="password" id="password" name="password" class="input<?= invalid('password') ?>"
                               placeholder="<?= $isEdit ? 'Leave blank to keep current' : 'At least 8 characters' ?>" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>
                               <?= field_error('password') ? 'aria-invalid="true" aria-describedby="password-error"' : '' ?>>
                    </div>
                    <?= error_text('password') ?>
                </div>

                <div class="field">
                    <label for="password_confirm">Confirm password<?= $isEdit ? '' : '<span class="req" aria-hidden="true">*</span>' ?></label>
                    <div class="input-wrap">
                        <?= icon('lock') ?>
                        <input type="password" id="password_confirm" name="password_confirm" class="input<?= invalid('password_confirm') ?>"
                               placeholder="Type the password again" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>
                               <?= field_error('password_confirm') ? 'aria-invalid="true" aria-describedby="password_confirm-error"' : '' ?>>
                    </div>
                    <?= error_text('password_confirm') ?>
                </div>
            </div>
            <?php if (! $isEdit): ?>
                <p class="help" style="margin-top:20px">You can add a profile picture from the Edit page once the account exists.</p>
            <?php endif ?>
        </div>

        <div class="form-actions">
            <a href="<?= site_url('users') ?>" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <?= icon($isEdit ? 'save' : 'user-plus') ?><?= $isEdit ? 'Save changes' : 'Create user' ?>
            </button>
        </div>
    </div>

    <?php if ($isEdit): ?>
        <aside class="card aside-card uploader">
            <h2 class="card-title">Profile picture</h2>
            <p class="card-sub" style="margin-bottom:22px">Shown on the User Accounts list.</p>

            <div class="uploader-preview">
                <img id="avatar-preview" src="<?= avatar_url($user['avatar'], 'display') ?>"
                     data-original="<?= avatar_url($user['avatar'], 'display') ?>"
                     alt="Current profile picture of <?= esc($user['full_name'], 'attr') ?>" width="148" height="148">
                <span class="uploader-badge"><?= icon('camera') ?></span>
            </div>

            <label for="avatar" class="dropzone<?= field_error('avatar') ? ' is-invalid' : '' ?>" id="dropzone">
                <?= icon('upload', 'icon-lg') ?>
                <strong><?= $user['avatar'] ? 'Replace picture' : 'Upload a picture' ?></strong>
                <span>Click or drop a file · JPG or PNG · max 2MB</span>
                <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                       <?= field_error('avatar') ? 'aria-invalid="true" aria-describedby="avatar-error"' : '' ?>>
            </label>
            <div class="file-name" id="file-name" aria-live="polite"></div>
            <?= error_text('avatar') ?>

            <div class="hint-box">
                <?= icon('info', 'icon-sm') ?>
                <span>The picture is cropped to a square, resized to a 400px display copy and a 96px thumbnail, and saved in <code>public/uploads/avatars</code>. Only the filename is stored in the database. Click “Save changes” to apply.</span>
            </div>
        </aside>
    <?php endif ?>
</form>

<?= $this->endSection() ?>

<?php if ($isEdit): ?>
<?= $this->section('scripts') ?>
<script>
(function () {
    var input   = document.getElementById('avatar');
    var preview = document.getElementById('avatar-preview');
    var zone    = document.getElementById('dropzone');
    var nameEl  = document.getElementById('file-name');
    var MAX     = 2 * 1024 * 1024;

    function show(file) {
        if (!file) { preview.src = preview.dataset.original; nameEl.textContent = ''; return; }
        var okType = /^image\/(jpeg|png)$/.test(file.type);
        var okSize = file.size <= MAX;
        nameEl.textContent = file.name + ' · ' + (file.size / 1024 / 1024).toFixed(2) + ' MB'
            + (!okType ? ' — not a JPG/PNG' : !okSize ? ' — larger than 2MB' : '');
        nameEl.style.color = okType && okSize ? '' : 'var(--danger)';
        if (okType) preview.src = URL.createObjectURL(file);
    }

    input.addEventListener('change', function () { show(input.files[0]); });

    ['dragenter', 'dragover'].forEach(function (ev) {
        zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add('is-drag'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
        zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.remove('is-drag'); });
    });
    zone.addEventListener('drop', function (e) {
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            show(input.files[0]);
        }
    });
})();
</script>
<?= $this->endSection() ?>
<?php endif ?>
