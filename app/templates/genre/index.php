<?php
/**
 * @var int $totalGenres
 * @var \System\Databases\Objects\Genre[] $genres
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<a href="<?= $route('genre.add'); ?>"><?= $this->t->genre->index->addNewLink; ?></a>
<?php if (isset($genres) && isset($totalGenres)): ?>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th><?= $this->t->genre->form->nameLabel; ?></th>
            <th><?= $this->t->genre->index->totalAlbums; ?></th>
            <th colspan="3"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="7"><?= $this->t->genre->index->tableFootPrefix; ?> <?= $totalGenres; ?> <?= $this->t->genre->index->tableFootSuffix; ?></td>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($genres as $genre): ?>
            <tr>
                <td><?= $genre->id; ?></td>
                <td><?= $genre->name; ?></td>
                <td><?= count($genre->albums); ?></td>
                <td><a href="<?= $route('genre.detail', ['id' => $genre->id]); ?>"><?= $this->t->genre->index->detailsLink; ?></a></td>
                <td><a href="<?= $route('genre.edit', ['id' => $genre->id]); ?>"><?= $this->t->genre->index->editLink; ?></a></td>
                <td><a href="<?= $route('genre.delete', ['id' => $genre->id]); ?>"><?= $this->t->genre->index->deleteLink; ?></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<div>
    <a href="<?= $route('home'); ?>"><?= $this->t->genre->index->backHomeLink; ?></a>
</div>
