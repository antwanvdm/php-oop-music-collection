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
<?= $yield('partials/success'); ?>

<?php if ($genre->id !== null): ?>
    <?= $yield('genre/form'); ?>
<?php endif; ?>

<a class="button mt-4" href="<?= $route('genre.index'); ?>"><?= $t('genre.backToListLink'); ?></a>
