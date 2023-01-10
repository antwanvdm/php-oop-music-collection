<?php
/**
 * @var \MusicCollection\Databases\Models\Artist $artist
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<a class="button is-danger mt-4" href="<?= $route('artists.delete', ['id' => $artist->id]); ?>?continue"><?= $t('artist.delete.verifyLink'); ?></a>
<a class="button mt-4" href="<?= $route('artists.index'); ?>"><?= $t('artist.delete.denyLink'); ?></a>
