<?php
/**
 * @var string|boolean $success
 * @var \System\Databases\Objects\Artist $artist
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($success !== false) { ?>
    <p class="success"><?= $success; ?></p>
<?php } ?>

<?php if ($artist->id !== null): ?>
    <?= $yield('artist/form'); ?>
<?php endif; ?>
<div>
    <a href="<?= $route('artist.index'); ?>"><?= $this->t->artist->backToListLink; ?></a>
</div>
