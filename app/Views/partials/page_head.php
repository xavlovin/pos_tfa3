<?php
/**
 * Page header: breadcrumb → title (H1) → subtitle, with actions on the right.
 *
 * @var array       $crumbs   [label => url|null]
 * @var string      $heading
 * @var string|null $subtitle
 * @var string|null $actions  pre-rendered HTML
 */
?>
<header class="page-head">
    <div>
        <nav class="crumbs" aria-label="Breadcrumb">
            <?php $i = 0; foreach ($crumbs as $label => $url): ?>
                <?php if ($i++ > 0): ?><?= icon('chevron-right') ?><?php endif ?>
                <?php if ($url): ?><a href="<?= $url ?>"><?= esc($label) ?></a><?php else: ?><span><?= esc($label) ?></span><?php endif ?>
            <?php endforeach ?>
        </nav>
        <h1 class="page-title"><?= esc($heading) ?></h1>
        <?php if (! empty($subtitle)): ?><p class="page-sub"><?= esc($subtitle) ?></p><?php endif ?>
    </div>
    <?php if (! empty($actions)): ?><div class="head-actions"><?= $actions ?></div><?php endif ?>
</header>
