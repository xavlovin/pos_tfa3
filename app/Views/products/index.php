<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?= view('partials/page_head', [
    'crumbs'   => ['Home' => site_url('/'), 'Products' => null],
    'heading'  => 'Products',
    'subtitle' => 'Catalogue carried over from TFA2 (read-only in this activity).',
]) ?>

<section class="card">
    <div class="card-head">
        <h2 class="card-title">All products <span class="count-pill"><?= count($products) ?></span></h2>
        <label class="search">
            <span class="sr-only">Search products</span>
            <?= icon('search') ?>
            <input type="search" class="input" placeholder="Search name, SKU, category…" data-filter="#products-table">
        </label>
    </div>

    <?php if ($products === []): ?>
        <div class="empty"><div class="empty-icon"><?= icon('box', 'icon-lg') ?></div><strong>No products yet</strong>Run the seeder or import the SQL file.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table" id="products-table">
                <thead>
                <tr><th>Product</th><th>Category</th><th class="num">Price</th><th class="num">Stock</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php foreach ($products as $p): ?>
                    <tr data-row>
                        <td>
                            <div class="cell-primary"><?= esc($p['name']) ?></div>
                            <div class="cell-secondary"><?= esc($p['sku']) ?></div>
                        </td>
                        <td><?= esc($p['category']) ?></td>
                        <td class="num cell-primary">₱<?= number_format((float) $p['price'], 2) ?></td>
                        <td class="num"><?= (int) $p['stock'] ?></td>
                        <td>
                            <?php if ((int) $p['stock'] === 0): ?>
                                <span class="badge badge-danger"><?= icon('alert', 'icon-sm') ?>Out of stock</span>
                            <?php elseif ((int) $p['stock'] < 10): ?>
                                <span class="badge badge-warning"><?= icon('warning', 'icon-sm') ?>Low stock</span>
                            <?php else: ?>
                                <span class="badge badge-success"><?= icon('check', 'icon-sm') ?>In stock</span>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
            <div class="empty" id="products-table-nomatch" hidden><strong>No matches</strong>Try a different search.</div>
        </div>
    <?php endif ?>
</section>

<?= $this->endSection() ?>
