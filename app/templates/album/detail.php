<?php
/**
 * @var \MusicCollection\Databases\Models\Album|false $album
 * @var bool $isLoggedIn
 * @var bool $isFavorite
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($album): ?>
    <img class="image is-128x128" src="<?= BASE_PATH . 'images/' . $album->image; ?>" alt="<?= $album->name; ?>"/>
    <section class="content">
        <ul>
            <li><?= $t('album.detail.genreLabel'); ?>
                <ul>
                    <?php foreach ($album->genres as $genre): ?>
                        <li><?= $genre->name; ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li><?= $t('album.detail.yearLabel'); ?> <?= $album->year; ?></li>
            <li><?= $t('album.detail.tracksLabel'); ?> <?= $album->tracks; ?></li>
        </ul>
    </section>
<?php endif; ?>

<a class="button" href="<?= $route('albums.index'); ?>"><?= $t('album.backToListLink'); ?></a>
<?php if ($isLoggedIn): ?>
    <i id="favorite-button" class="button <?= $isFavorite ? 'favorite' : '' ?>" data-id="<?= $album->id; ?>"></i>
<?php endif; ?>
