<?php
/**
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>
<?= $yield('partials/success'); ?>
<?= $yield('genre/form'); ?>
<a class="button mt-4" href="<?= $route('genre.index'); ?>"><?= $t('genre.backToListLink'); ?></a>
