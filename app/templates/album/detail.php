<?php
/**
 * @var \MusicCollection\Databases\Objects\Album|false $album
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

<a class="button" href="<?= $route('album.index'); ?>"><?= $t('album.backToListLink'); ?></a>
