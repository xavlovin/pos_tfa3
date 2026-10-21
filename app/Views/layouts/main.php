<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'POS') ?> · Sari POS</title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
<?= view('partials/sprite') ?>

<div class="shell">
    <?= view('partials/sidebar', ['active' => $active ?? '']) ?>

    <main class="main" id="main">
        <?= view('partials/flash') ?>
        <?= $this->renderSection('content') ?>
    </main>
</div>

<script>
    // Dismissible flash messages
    document.querySelectorAll('[data-dismiss]').forEach(function (btn) {
        btn.addEventListener('click', function () { btn.closest('.alert').remove(); });
    });

    // Client-side quick filter for listing tables
    document.querySelectorAll('[data-filter]').forEach(function (input) {
        var rows = document.querySelectorAll(input.dataset.filter + ' tbody tr[data-row]');
        var empty = document.querySelector(input.dataset.filter + '-nomatch');
        input.addEventListener('input', function () {
            var q = input.value.trim().toLowerCase(), shown = 0;
            rows.forEach(function (row) {
                var hit = row.textContent.toLowerCase().indexOf(q) !== -1;
                row.hidden = !hit;
                if (hit) shown++;
            });
            if (empty) empty.hidden = shown !== 0;
        });
    });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
