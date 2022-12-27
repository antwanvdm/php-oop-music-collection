<?php
/**
 * @var int $totalGenres
 * @var \MusicCollection\Databases\Objects\Genre[] $genres
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<a class="button is-primary" href="<?= $route('home'); ?>"><?= $t('general.backHomeLink'); ?></a>
<a class="button" href="<?= $route('genre.add'); ?>"><?= $t('genre.index.addNewLink'); ?></a>
<table class="table is-striped mt-4">
    <thead>
    <tr>
        <th>#</th>
        <th><?= $t('genre.form.nameLabel'); ?></th>
        <th><?= $t('genre.index.totalAlbums'); ?></th>
        <th colspan="3"></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="6" class="has-text-centered"><?= $t('genre.index.tableFootPrefix'); ?> <?= $totalGenres; ?> <?= $t('genre.index.tableFootSuffix'); ?></td>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach ($genres as $genre): ?>
        <tr>
            <td><?= $genre->id; ?></td>
            <td><?= $genre->name; ?></td>
            <td><?= count($genre->albums); ?></td>
            <td><a href="<?= $route('genre.detail', ['id' => $genre->id]); ?>"><?= $t('genre.index.detailsLink'); ?></a></td>
            <td><a href="<?= $route('genre.edit', ['id' => $genre->id]); ?>"><?= $t('genre.index.editLink'); ?></a></td>
            <td><a href="<?= $route('genre.delete', ['id' => $genre->id]); ?>"><?= $t('genre.index.deleteLink'); ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
