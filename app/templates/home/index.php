<?php
/**
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<ul>
    <li><a href="<?= $route('artist.index'); ?>"><?= $this->t->home->artistsLink; ?></a></li>
    <li><a href="<?= $route('album.index'); ?>"><?= $this->t->home->albumsLink; ?></a></li>
    <li><a href="<?= $route('genre.index'); ?>"><?= $this->t->home->genresLink; ?></a></li>
</ul>
