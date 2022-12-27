<?php
/**
 * @var int $totalArtists
 * @var \MusicCollection\Databases\Objects\Artist[] $artists
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<a class="button is-primary" href="<?= $route('home'); ?>"><?= $t('general.backHomeLink'); ?></a>
<a class="button" href="<?= $route('artist.add'); ?>"><?= $t('artist.index.addNewLink'); ?></a>
<table class="table is-striped mt-4">
    <thead>
    <tr>
        <th>#</th>
        <th><?= $t('artist.form.nameLabel'); ?></th>
        <th><?= $t('artist.index.totalAlbums'); ?></th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="6" class="has-text-centered"><?= $t('artist.index.tableFootPrefix'); ?> <?= $totalArtists; ?> <?= $t('artist.index.tableFootSuffix'); ?></td>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach ($artists as $artist): ?>
        <tr>
            <td><?= $artist->id; ?></td>
            <td><?= $artist->name; ?></td>
            <td><?= count($artist->albums); ?></td>
            <td><a href="<?= $route('artist.detail', ['id' => $artist->id]); ?>"><?= $t('artist.index.detailsLink'); ?></a></td>
            <td><a href="<?= $route('artist.edit', ['id' => $artist->id]); ?>"><?= $t('artist.index.editLink'); ?></a></td>
            <td><a href="<?= $route('artist.delete', ['id' => $artist->id]); ?>"><?= $t('artist.index.deleteLink'); ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
