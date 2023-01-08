<?php
/**
 * @var string|boolean $success
 * @var \MusicCollection\Databases\Objects\Album $album
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>
<?= $yield('partials/success'); ?>

<?php if ($album->id !== null): ?>
    <?= $yield('album/form'); ?>
<?php endif; ?>

<a class="button mt-4" href="<?= $route('albums.index'); ?>"><?= $t('album.backToListLink'); ?></a>
<a class="button mt-4 is-danger" href="<?= $route('account.logout'); ?>"><?= $t('general.logoutLink'); ?></a>
