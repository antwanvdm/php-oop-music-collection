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

<?= $yield('artist/form'); ?>
<div>
    <a href="<?= $route('artist.index'); ?>"><?= $this->t->artist->backToListLink; ?></a>
    <a href="<?= $route('account.logout'); ?>"><?= $this->t->artist->add->logoutLink; ?></a>
</div>
