<?php
$isEdit = $customer !== null;
// Previous input wins (after a failed submit); otherwise the stored value. Always escaped.
$val    = static fn (string $field) => esc((string) old($field, $customer[$field] ?? '', false));
?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?= view('partials/page_head', [
    'crumbs'   => ['Home' => site_url('/'), 'Customers' => site_url('customers'), ($isEdit ? 'Edit' : 'New') => null],
    'heading'  => $isEdit ? 'Edit ' . $customer['full_name'] : 'New Customer',
    'subtitle' => $isEdit
        ? 'Update this customer’s details. Changes are saved when you click “Save changes”.'
        : 'Add someone who buys from the store. Fields marked * are required.',
    'actions'  => '<a href="' . site_url('customers') . '" class="btn btn-ghost">' . icon('arrow-left') . 'Back to customers</a>',
]) ?>

<div class="form-layout<?= $isEdit ? '' : ' single' ?>">
    <form action="<?= $action ?>" method="post" class="card" novalidate>
        <?= csrf_field() ?>
        <?= view('partials/error_summary') ?>

        <div class="form-section">
            <h2 class="section-title"><?= icon('user') ?>Basic information</h2>
            <p class="section-sub">Used on receipts and when looking up the customer.</p>

            <div class="fields">
                <div class="field field-full">
                    <label for="full_name">Full name<span class="req" aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                        <?= icon('user') ?>
                        <input type="text" id="full_name" name="full_name" class="input<?= invalid('full_name') ?>"
                               value="<?= $val('full_name') ?>" placeholder="e.g. Maria Clara Santos"
                               maxlength="120" required autofocus
                               <?= field_error('full_name') ? 'aria-invalid="true" aria-describedby="full_name-error"' : '' ?>>
                    </div>
                    <?= error_text('full_name') ?>
                </div>

                <div class="field field-full">
                    <label for="email">Email address<span class="req" aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                        <?= icon('mail') ?>
                        <input type="email" id="email" name="email" class="input<?= invalid('email') ?>"
                               value="<?= $val('email') ?>" placeholder="name@example.com"
                               maxlength="150" required
                               <?= field_error('email') ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>>
                    </div>
                    <?= error_text('email') ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2 class="section-title"><?= icon('pin') ?>Contact details</h2>
            <p class="section-sub">Optional — helpful for deliveries and follow-ups.</p>

            <div class="fields">
                <div class="field">
                    <label for="phone">Phone<span class="opt">Optional</span></label>
                    <div class="input-wrap">
                        <?= icon('phone') ?>
                        <input type="tel" id="phone" name="phone" class="input<?= invalid('phone') ?>"
                               value="<?= $val('phone') ?>" placeholder="0917 123 4567" maxlength="30"
                               <?= field_error('phone') ? 'aria-invalid="true" aria-describedby="phone-error"' : '' ?>>
                    </div>
                    <?= error_text('phone') ?>
                </div>

                <div class="field">
                    <label for="address">Address<span class="opt">Optional</span></label>
                    <div class="input-wrap">
                        <?= icon('pin') ?>
                        <input type="text" id="address" name="address" class="input<?= invalid('address') ?>"
                               value="<?= $val('address') ?>" placeholder="City or full address" maxlength="255"
                               <?= field_error('address') ? 'aria-invalid="true" aria-describedby="address-error"' : '' ?>>
                    </div>
                    <?= error_text('address') ?>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="<?= site_url('customers') ?>" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <?= icon($isEdit ? 'save' : 'plus') ?><?= $isEdit ? 'Save changes' : 'Create customer' ?>
            </button>
        </div>
    </form>

    <?php if ($isEdit): ?>
        <aside class="card aside-card">
            <div class="cell-person" style="margin-bottom:22px">
                <span class="monogram" style="width:56px;height:56px;font-size:18px"><?= esc(initials($customer['full_name'])) ?></span>
                <div>
                    <div class="cell-primary"><?= esc($customer['full_name']) ?></div>
                    <div class="cell-secondary">Customer #<?= (int) $customer['id'] ?></div>
                </div>
            </div>
            <ul class="meta-list">
                <li><span class="meta-icon"><?= icon('calendar', 'icon-sm') ?></span><div><small>Added</small><b><?= nice_date($customer['created_at']) ?></b></div></li>
                <li><span class="meta-icon"><?= icon('pencil', 'icon-sm') ?></span><div><small>Last updated</small><b><?= nice_date($customer['updated_at']) ?></b></div></li>
            </ul>
        </aside>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
