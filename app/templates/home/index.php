<?php
/**
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<section class="content">
    <a class="button is-primary" href="<?= $route('artists.index'); ?>"><?= $t('home.artistsLink'); ?></a>
    <a class="button is-primary" href="<?= $route('albums.index'); ?>"><?= $t('home.albumsLink'); ?></a>
    <a class="button is-primary" href="<?= $route('genres.index'); ?>"><?= $t('home.genresLink'); ?></a>
</section>
