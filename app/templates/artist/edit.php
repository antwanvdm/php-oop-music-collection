<?php
/**
 * @var string|boolean $success
 * @var \MusicCollection\Databases\Objects\Artist $artist
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>
<?= $yield('partials/success'); ?>

<?php if ($artist->id !== null): ?>
    <?= $yield('artist/form'); ?>
<?php endif; ?>

<a class="button mt-4" href="<?= $route('artists.index'); ?>"><?= $t('artist.backToListLink'); ?></a>
<a class="button mt-4 is-danger" href="<?= $route('user.logout'); ?>"><?= $t('general.logoutLink'); ?></a>
