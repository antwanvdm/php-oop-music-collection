<?php
/**
 * @var array $errors
 * @var int $totalAlbums
 * @var \System\Databases\Objects\Album[] $albums
 * @var callable $route
 */
?>
<h1>Albums</h1>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= $route('album.add'); ?>">Add new album</a>
<?php if (isset($albums) && isset($totalAlbums)): ?>
    <table>
        <thead>
        <tr>
            <th></th>
            <th>#</th>
            <th>Artist</th>
            <th>Name</th>
            <th>Genre</th>
            <th>Year</th>
            <th>Tracks</th>
            <th colspan="3"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="10">&copy; My Collection with <?= $totalAlbums; ?> albums</td>
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
                <td><a href="<?= $route('album.detail', ['id' => $album->id]); ?>">Details</a></td>
                <td><a href="<?= $route('album.edit', ['id' => $album->id]); ?>">Edit</a></td>
                <td><a href="<?= $route('album.delete', ['id' => $album->id]); ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<div>
    <a href="<?= $route('home'); ?>">Go back home</a>
</div>
