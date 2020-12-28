<?php
/**
 * @var int $totalArtists
 * @var \System\Databases\Objects\Artist[] $artists
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<a href="<?= $route('artist.add'); ?>"><?= $this->t->artist->index->addNewLink; ?></a>
<?php if (isset($artists) && isset($totalArtists)): ?>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th><?= $this->t->artist->form->nameLabel; ?></th>
            <th><?= $this->t->artist->index->totalAlbums; ?></th>
            <th colspan="3"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="7"><?= $this->t->artist->index->tableFootPrefix; ?> <?= $totalArtists; ?> <?= $this->t->artist->index->tableFootSuffix; ?></td>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($artists as $artist): ?>
            <tr>
                <td><?= $artist->id; ?></td>
                <td><?= $artist->name; ?></td>
                <td><?= count($artist->albums); ?></td>
                <td><a href="<?= $route('artist.detail', ['id' => $artist->id]); ?>"><?= $this->t->artist->index->detailsLink; ?></a></td>
                <td><a href="<?= $route('artist.edit', ['id' => $artist->id]); ?>"><?= $this->t->artist->index->editLink; ?></a></td>
                <td><a href="<?= $route('artist.delete', ['id' => $artist->id]); ?>"><?= $this->t->artist->index->deleteLink; ?></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<div>
    <a href="<?= $route('home'); ?>"><?= $this->t->artist->index->backHomeLink; ?></a>
</div>
