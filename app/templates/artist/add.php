<?php
/**
 * @var string|boolean $success
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */

?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>
<?= $yield('partials/success'); ?>
<?= $yield('artist/form'); ?>

<a class="button mt-4" href="<?= $route('artist.index'); ?>"><?= $t('artist.backToListLink'); ?></a>
<a class="button mt-4 is-danger" href="<?= $route('account.logout'); ?>"><?= $t('general.logoutLink'); ?></a>
