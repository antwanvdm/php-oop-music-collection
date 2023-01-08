<?php
/**
 * @var string|boolean $success
 * @var callable $route
 * @var callable $yield
 * @var callable $t * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>
<?= $yield('partials/success'); ?>
<?= $yield('album/form'); ?>

<a class="button mt-4" href="<?= $route('albums.index'); ?>"><?= $t('album.backToListLink'); ?></a>
<a class="button mt-4 is-danger" href="<?= $route('account.logout'); ?>"><?= $t('general.logoutLink'); ?></a>
