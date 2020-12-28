<?php
/**
 * @var string|boolean $success
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($success !== false) { ?>
    <p class="success"><?= $success; ?></p>
<?php } ?>

<?= $yield('album/form'); ?>
<div>
    <a href="<?= $route('album.index'); ?>"><?= $this->t->album->backToListLink; ?></a>
    <a href="<?= $route('account.logout'); ?>"><?= $this->t->album->add->logoutLink; ?></a>
</div>
