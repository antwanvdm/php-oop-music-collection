<?php
/**
 * @var \MusicCollection\Databases\Models\Artist|false $artist
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($artist): ?>
    <section class="content">
        <ul>
            <li><?= $t('artist.detail.albumLabel'); ?>
                <ul>
                    <?php foreach ($artist->albums() as $album): ?>
                        <li><?= $album->name; ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </section>
<?php endif; ?>

<a class="button" href="<?= $route('artists.index'); ?>"><?= $t('artist.backToListLink'); ?></a>
