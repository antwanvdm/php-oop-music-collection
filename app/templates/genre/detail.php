<?php
/**
 * @var \MusicCollection\Databases\Models\Genre|false $genre
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($genre): ?>
    <section class="content">
        <ul>
            <li><?= $t('genre.detail.albumLabel'); ?>
                <ul>
                    <?php foreach ($genre->albums as $album): ?>
                        <li><?= $album->name; ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </section>
<?php endif; ?>

<a class="button" href="<?= $route('genres.index'); ?>"><?= $t('genre.backToListLink'); ?></a>
