<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?= view('partials/page_head', [
    'crumbs'   => ['Home' => site_url('/'), 'User Accounts' => null],
    'heading'  => 'User Accounts',
    'subtitle' => 'Staff who can sign in to the POS. Edit an account to change details or upload a profile picture.',
    'actions'  => '<a href="' . site_url('users/new') . '" class="btn btn-primary">' . icon('user-plus') . 'New User</a>',
]) ?>

<?php $highlight = (int) session('highlight'); ?>

<section class="card">
    <div class="card-head">
        <h2 class="card-title">All users <span class="count-pill"><?= count($users) ?></span></h2>
        <label class="search">
            <span class="sr-only">Search users</span>
            <?= icon('search') ?>
            <input type="search" class="input" placeholder="Search name, username, role…" data-filter="#users-table">
        </label>
    </div>

    <?php if ($users === []): ?>
        <div class="empty">
            <div class="empty-icon"><?= icon('users', 'icon-lg') ?></div>
            <strong>No user accounts yet</strong>
            Click “New User” to create the first one.
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table" id="users-table">
                <thead>
                <tr><th>User</th><th>Username</th><th>Email</th><th>Role</th><th>Added</th><th class="actions"><span class="sr-only">Actions</span></th></tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr data-row class="<?= $highlight === (int) $u['id'] ? 'is-new' : '' ?>">
                        <td>
                            <div class="cell-person">
                                <span class="avatar">
                                    <img src="<?= avatar_url($u['avatar'], 'thumb') ?>"
                                         alt="<?= $u['avatar'] ? 'Profile picture of ' . esc($u['full_name'], 'attr') : 'No profile picture' ?>"
                                         width="40" height="40" loading="lazy">
                                </span>
                                <div class="cell-primary"><?= esc($u['full_name']) ?></div>
                            </div>
                        </td>
                        <td>@<?= esc($u['username']) ?></td>
                        <td><?= $u['email'] ? esc($u['email']) : '<span class="muted">—</span>' ?></td>
                        <td><span class="badge badge-<?= esc($u['role'], 'attr') ?>"><?= icon('shield', 'icon-sm') ?><?= esc($roles[$u['role']] ?? ucfirst($u['role'])) ?></span></td>
                        <td class="muted"><?= nice_date($u['created_at']) ?></td>
                        <td class="actions">
                            <a href="<?= site_url('users/' . $u['id'] . '/edit') ?>" class="btn">
                                <?= icon('pencil', 'icon-sm') ?>Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
            <div class="empty" id="users-table-nomatch" hidden><strong>No matches</strong>Try a different search.</div>
        </div>
    <?php endif ?>
</section>

<?= $this->endSection() ?>
