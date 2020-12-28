<?php
/**
 * @var string|bool $success
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($success !== false) { ?>
    <p class="success"><?= $success; ?></p>
<?php } ?>

<?= $yield('genre/form'); ?>
<div>
    <a href="<?= $route('genre.index'); ?>"><?= $this->t->genre->backToListLink; ?></a>
</div>
