<?php
/**
 * @var \MusicCollection\Databases\Objects\Genre $genre
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<a class="button is-danger mt-4" href="<?= $route('genres.delete', ['id' => $genre->id]); ?>?continue"><?= $t('genre.delete.verifyLink'); ?></a>
<a class="button mt-4" href="<?= $route('genres.index'); ?>"><?= $t('genre.delete.denyLink'); ?></a>
