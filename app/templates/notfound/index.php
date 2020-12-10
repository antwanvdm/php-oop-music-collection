<?php
/**
 * @var string $pageTitle
 * @var callable $route
 */
?>
<h1><?= $pageTitle; ?></h1>
<p>Navigeer naar een andere URL! <a href="<?= $route('home'); ?>">Ga naar home</a></p>
