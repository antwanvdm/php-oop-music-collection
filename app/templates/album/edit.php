<?php
/**
 * @var string|boolean $success
 * @var \System\Databases\Objects\Album $album
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($success !== false) { ?>
    <p class="success"><?= $success; ?></p>
<?php } ?>

<?php if ($album->id !== null): ?>
    <?= $yield('album/form'); ?>
<?php endif; ?>
<div>
    <a href="<?= $route('album.index'); ?>"><?= $this->t->album->backToListLink; ?></a>
</div>
