<?php
/**
 * @var $pageTitle string
 * @var $content string
 */
?>
<!doctype html>
<html lang="<?= DEFAULT_LANGUAGE; ?>">
<head>
    <title><?= $this->t->general->siteName; ?> | <?= ($pageTitle ?? ''); ?></title>
    <meta charset="utf-8"/>
    <link type="text/css" rel="stylesheet" href="<?= RESOURCES_PATH; ?>css/style.css"/>
</head>
<body>
<?= ($content ?? ''); ?>
</body>
</html>
