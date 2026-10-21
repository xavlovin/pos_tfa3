<?php
$links = [
    'dashboard' => ['Dashboard', 'grid', ''],
    'products'  => ['Products', 'box', 'products'],
    'customers' => ['Customers', 'contact', 'customers'],
    'users'     => ['User Accounts', 'users', 'users'],
];
?>
<aside class="sidebar">
    <a href="<?= site_url('/') ?>" class="brand">
        <span class="brand-mark"><?= icon('store', 'icon-lg') ?></span>
        <span>
            <span class="brand-name">Sari POS</span><br>
            <span class="brand-sub">Point of Sale</span>
        </span>
    </a>

    <nav>
        <p class="nav-label">Menu</p>
        <div class="nav">
            <?php foreach ($links as $key => [$label, $iconName, $path]): ?>
                <a href="<?= site_url($path) ?>" class="<?= $active === $key ? 'active' : '' ?>"
                   <?= $active === $key ? 'aria-current="page"' : '' ?>>
                    <span class="nav-icon"><?= icon($iconName) ?></span>
                    <?= esc($label) ?>
                </a>
            <?php endforeach ?>
        </div>
    </nav>

    <div class="sidebar-foot">
        <strong>IT0049 · TFA3</strong>
        Forms, Validation &amp; File Upload
    </div>
</aside>
