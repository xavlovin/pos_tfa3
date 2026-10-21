<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?= view('partials/page_head', [
    'crumbs'   => ['Home' => site_url('/'), 'Customers' => null],
    'heading'  => 'Customers',
    'subtitle' => 'People who buy from the store. Add a new customer or edit an existing record.',
    'actions'  => '<a href="' . site_url('customers/new') . '" class="btn btn-primary">' . icon('plus') . 'New Customer</a>',
]) ?>

<section class="card">
    <div class="card-head">
        <h2 class="card-title">All customers <span class="count-pill"><?= count($customers) ?></span></h2>
        <label class="search">
            <span class="sr-only">Search customers</span>
            <?= icon('search') ?>
            <input type="search" class="input" placeholder="Search name, email, phone…" data-filter="#customers-table">
        </label>
    </div>

    <?php if ($customers === []): ?>
        <div class="empty">
            <div class="empty-icon"><?= icon('contact', 'icon-lg') ?></div>
            <strong>No customers yet</strong>
            Click “New Customer” to add the first one.
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table" id="customers-table">
                <thead>
                <tr><th>Customer</th><th>Phone</th><th>Address</th><th>Added</th><th class="actions"><span class="sr-only">Actions</span></th></tr>
                </thead>
                <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr data-row>
                        <td>
                            <div class="cell-person">
                                <span class="monogram"><?= esc(initials($c['full_name'])) ?></span>
                                <div>
                                    <div class="cell-primary"><?= esc($c['full_name']) ?></div>
                                    <div class="cell-secondary"><?= esc($c['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= $c['phone'] ? esc($c['phone']) : '<span class="muted">—</span>' ?></td>
                        <td><?= $c['address'] ? esc($c['address']) : '<span class="muted">—</span>' ?></td>
                        <td class="muted"><?= nice_date($c['created_at']) ?></td>
                        <td class="actions">
                            <a href="<?= site_url('customers/' . $c['id'] . '/edit') ?>" class="btn">
                                <?= icon('pencil', 'icon-sm') ?>Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
            <div class="empty" id="customers-table-nomatch" hidden><strong>No matches</strong>Try a different search.</div>
        </div>
    <?php endif ?>
</section>

<?= $this->endSection() ?>
