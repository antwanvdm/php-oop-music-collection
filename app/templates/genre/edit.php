<?php
/**
 * @var string|boolean $success
 * @var \System\Databases\Objects\Genre $genre
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($success !== false) { ?>
    <p class="success"><?= $success; ?></p>
<?php } ?>

<?php if ($genre->id !== null): ?>
    <?= $yield('genre/form'); ?>
<?php endif; ?>
<div>
    <a href="<?= $route('genre.index'); ?>"><?= $this->t->genre->backToListLink; ?></a>
</div>
