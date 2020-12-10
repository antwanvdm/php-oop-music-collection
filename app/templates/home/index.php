<?php
/**
 * @var string $pageTitle
 * @var callable $route
 */
?>
<h1><?= $pageTitle; ?></h1>
<ul>
    <li><a href="<?= $route('artist.index'); ?>">Artists</a></li>
    <li><a href="<?= $route('album.index'); ?>">Albums</a></li>
    <li><a href="<?= $route('genre.index'); ?>">Genres</a></li>
</ul>
