<?php
/**
 * @var $errors array
 * @var $totalGenres int
 * @var $genres \System\Databases\Objects\Genre[]
 */
?>
<h1>Genres</h1>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= BASE_PATH; ?>genres/add">Add new genre</a>
<?php if (isset($genres) && isset($totalGenres)): ?>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Total Albums</th>
            <th colspan="3"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="7">&copy; My Collection with <?= $totalGenres; ?> genres</td>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($genres as $genre): ?>
            <tr>
                <td><?= $genre->id; ?></td>
                <td><?= $genre->name; ?></td>
                <td><?= count($genre->albums); ?></td>
                <td><a href="<?= BASE_PATH; ?>genres/detail?id=<?= $genre->id; ?>">Details</a></td>
                <td><a href="<?= BASE_PATH; ?>genres/edit?id=<?= $genre->id; ?>">Edit</a></td>
                <td><a href="<?= BASE_PATH; ?>genres/delete?id=<?= $genre->id; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<div>
    <a href="<?= BASE_PATH; ?>">Go back home</a>
</div>
