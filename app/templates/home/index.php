<?php
/**
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<section class="content">
    <a class="button is-primary" href="<?= $route('artist.index'); ?>"><?= $t('home.artistsLink'); ?></a>
    <a class="button is-primary" href="<?= $route('album.index'); ?>"><?= $t('home.albumsLink'); ?></a>
    <a class="button is-primary" href="<?= $route('genre.index'); ?>"><?= $t('home.genresLink'); ?></a>
</section>
