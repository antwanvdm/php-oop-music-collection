<?php
/**
 * @var int $totalAlbums
 * @var \MusicCollection\Databases\Models\Album[] $albums
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<a class="button is-primary" href="<?= $route('home'); ?>"><?= $t('general.backHomeLink'); ?></a>
<a class="button" href="<?= $route('albums.create'); ?>"><?= $t('album.index.createNewLink'); ?></a>
<table class="table is-striped mt-4 is-fullwidth">
    <thead>
    <tr>
        <th></th>
        <th>#</th>
        <th><?= $t('album.form.artistLabel'); ?></th>
        <th><?= $t('album.form.nameLabel'); ?></th>
        <th><?= $t('album.form.recordingLabel'); ?></th>
        <th><?= $t('album.form.genreLabel'); ?></th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="10" class="has-text-centered"><?= $t('album.index.tableFoot', ['TOTAL' => $totalAlbums]); ?></td>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach ($albums as $album): ?>
        <tr>
            <td class="is-vcentered">
                <img class="image is-64x64" src="<?= BASE_PATH . 'images/' . $album->image; ?>" alt="<?= $album->name; ?>"/>
            </td>
            <td class="is-vcentered"><?= $album->id; ?></td>
            <td class="is-vcentered"><?= $album->artist->name; ?></td>
            <td class="is-vcentered"><?= $album->name; ?></td>
            <td class="is-vcentered"><?= $album->recording->label(); ?></td>
            <td class="is-vcentered">
                <ul>
                    <?php foreach ($album->genres as $genre): ?>
                        <li><?= $genre->name; ?></li>
                    <?php endforeach; ?>
                </ul>
            </td>
            <td class="is-vcentered"><a href="<?= $route('albums.detail', ['id' => $album->id]); ?>"><?= $t('album.index.detailsLink'); ?></a></td>
            <td class="is-vcentered"><a href="<?= $route('albums.edit', ['id' => $album->id]); ?>"><?= $t('album.index.editLink'); ?></a></td>
            <td class="is-vcentered"><a href="<?= $route('albums.delete', ['id' => $album->id]); ?>"><?= $t('album.index.deleteLink'); ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
