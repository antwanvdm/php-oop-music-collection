<?php
/**
 * @var string $pageTitle
 * @var array $errors
 * @var int $totalAlbums
 * @var \System\Databases\Objects\Album[] $albums
 * @var callable $route
 */
?>
<h1><?= $pageTitle; ?></h1>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= $route('album.add'); ?>"><?= $this->t->album->index->addNewLink; ?></a>
<?php if (isset($albums) && isset($totalAlbums)): ?>
    <table>
        <thead>
        <tr>
            <th></th>
            <th>#</th>
            <th><?= $this->t->album->form->artistLabel; ?></th>
            <th><?= $this->t->album->form->nameLabel; ?></th>
            <th><?= $this->t->album->form->genreLabel; ?></th>
            <th><?= $this->t->album->form->yearLabel; ?></th>
            <th><?= $this->t->album->form->tracksLabel; ?></th>
            <th colspan="3"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="10"><?= $this->t->album->index->tableFootPrefix; ?> <?= $totalAlbums; ?> <?= $this->t->album->index->tableFootSuffix; ?></td>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($albums as $album): ?>
            <tr>
                <td class="image"><img src="<?= RESOURCES_PATH . $album->image; ?>" alt="<?= $album->name; ?>"/></td>
                <td><?= $album->id; ?></td>
                <td><?= $album->artist->name; ?></td>
                <td><?= $album->name; ?></td>
                <td>
                    <ul>
                        <?php foreach ($album->genres as $genre): ?>
                            <li><?= $genre->name; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
                <td><?= $album->year; ?></td>
                <td><?= $album->tracks; ?></td>
                <td><a href="<?= $route('album.detail', ['id' => $album->id]); ?>"><?= $this->t->album->index->detailsLink; ?></a></td>
                <td><a href="<?= $route('album.edit', ['id' => $album->id]); ?>"><?= $this->t->album->index->editLink; ?></a></td>
                <td><a href="<?= $route('album.delete', ['id' => $album->id]); ?>"><?= $this->t->album->index->deleteLink; ?></a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<div>
    <a href="<?= $route('home'); ?>"><?= $this->t->album->index->backHomeLink; ?></a>
</div>
