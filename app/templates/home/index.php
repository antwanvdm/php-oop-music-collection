<?php
/**
 * @var string $pageTitle
 * @var callable $route
 */
?>
<h1><?= $pageTitle; ?></h1>
<ul>
    <li><a href="<?= $route('artist.index'); ?>"><?= $this->t->home->artistsLink; ?></a></li>
    <li><a href="<?= $route('album.index'); ?>"><?= $this->t->home->albumsLink; ?></a></li>
    <li><a href="<?= $route('genre.index'); ?>"><?= $this->t->home->genresLink; ?></a></li>
</ul>
