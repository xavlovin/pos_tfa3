<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?= view('partials/page_head', [
    'crumbs'   => ['Home' => null],
    'heading'  => 'Good day! Here’s your store at a glance',
    'subtitle' => 'Manage customers and staff accounts, and keep an eye on stock levels.',
    'actions'  => '<a href="' . site_url('customers/new') . '" class="btn">' . icon('plus') . 'New Customer</a>'
                . '<a href="' . site_url('users/new') . '" class="btn btn-primary">' . icon('user-plus') . 'New User</a>',
]) ?>

<section class="stats" aria-label="Summary">
    <a href="<?= site_url('customers') ?>" class="stat">
        <div class="stat-top">
            <span class="stat-icon"><?= icon('contact', 'icon-lg') ?></span>
            <span class="stat-go"><?= icon('arrow-up-right') ?></span>
        </div>
        <div>
            <div class="stat-value"><?= number_format($customerCount) ?></div>
            <div class="stat-label">Customers</div>
        </div>
    </a>
    <a href="<?= site_url('users') ?>" class="stat">
        <div class="stat-top">
            <span class="stat-icon is-success"><?= icon('users', 'icon-lg') ?></span>
            <span class="stat-go"><?= icon('arrow-up-right') ?></span>
        </div>
        <div>
            <div class="stat-value"><?= number_format($userCount) ?></div>
            <div class="stat-label">User Accounts</div>
        </div>
    </a>
    <a href="<?= site_url('products') ?>" class="stat">
        <div class="stat-top">
            <span class="stat-icon"><?= icon('box', 'icon-lg') ?></span>
            <span class="stat-go"><?= icon('arrow-up-right') ?></span>
        </div>
        <div>
            <div class="stat-value"><?= number_format($productCount) ?></div>
            <div class="stat-label">Products</div>
        </div>
    </a>
    <a href="<?= site_url('products') ?>" class="stat">
        <div class="stat-top">
            <span class="stat-icon is-warning"><?= icon('warning', 'icon-lg') ?></span>
            <span class="stat-go"><?= icon('arrow-up-right') ?></span>
        </div>
        <div>
            <div class="stat-value"><?= number_format($lowStockCount) ?></div>
            <div class="stat-label">Low / Out of Stock</div>
        </div>
    </a>
</section>

<div class="grid-2">
    <section class="card">
        <div class="card-head">
            <div>
                <h2 class="card-title">Recent customers</h2>
                <p class="card-sub">The five most recently added</p>
            </div>
            <a href="<?= site_url('customers') ?>" class="btn btn-ghost">View all <?= icon('chevron-right', 'icon-sm') ?></a>
        </div>
        <?php if ($recentCustomers === []): ?>
            <div class="empty"><div class="empty-icon"><?= icon('inbox', 'icon-lg') ?></div><strong>No customers yet</strong></div>
        <?php else: ?>
            <ul class="mini-list">
                <?php foreach ($recentCustomers as $c): ?>
                    <li>
                        <div class="cell-person">
                            <span class="monogram"><?= esc(initials($c['full_name'])) ?></span>
                            <div>
                                <div class="cell-primary"><?= esc($c['full_name']) ?></div>
                                <div class="cell-secondary"><?= esc($c['email']) ?></div>
                            </div>
                        </div>
                        <a href="<?= site_url('customers/' . $c['id'] . '/edit') ?>" class="btn btn-icon" title="Edit <?= esc($c['full_name'], 'attr') ?>">
                            <?= icon('pencil', 'icon-sm') ?><span class="sr-only">Edit</span>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </section>

    <section class="card">
        <div class="card-head">
            <div>
                <h2 class="card-title">Recent user accounts</h2>
                <p class="card-sub">Staff who can sign in to the POS</p>
            </div>
            <a href="<?= site_url('users') ?>" class="btn btn-ghost">View all <?= icon('chevron-right', 'icon-sm') ?></a>
        </div>
        <?php if ($recentUsers === []): ?>
            <div class="empty"><div class="empty-icon"><?= icon('inbox', 'icon-lg') ?></div><strong>No users yet</strong></div>
        <?php else: ?>
            <ul class="mini-list">
                <?php foreach ($recentUsers as $u): ?>
                    <li>
                        <div class="cell-person">
                            <span class="avatar avatar-sm"><img src="<?= avatar_url($u['avatar']) ?>" alt="" loading="lazy"></span>
                            <div>
                                <div class="cell-primary"><?= esc($u['full_name']) ?></div>
                                <div class="cell-secondary">@<?= esc($u['username']) ?></div>
                            </div>
                        </div>
                        <span class="badge badge-<?= esc($u['role'], 'attr') ?>"><?= esc(ucfirst($u['role'])) ?></span>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </section>
</div>

<?= $this->endSection() ?>
