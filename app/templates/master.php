<?php
/**
 * @var string|null $pageTitle
 * @var string|null $content
 * @var callable $t
 */
?>
<!doctype html>
<html lang="<?= DEFAULT_LANGUAGE; ?>">
<head>
    <title><?= $t('general.siteName'); ?> | <?= ($pageTitle ?? ''); ?></title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script type="text/javascript" src="<?= BASE_PATH ?>js/main.js" defer></script>
</head>
<body>
<div class="container px-4">
    <?= ($content ?? ''); ?>
</div>
</body>
</html>
