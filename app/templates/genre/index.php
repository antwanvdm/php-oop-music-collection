<?php
/**
 * @var array $errors
 * @var int $totalGenres
 * @var \System\Databases\Objects\Genre[] $genres
 * @var callable $route
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

<a href="<?= $route('genre.add'); ?>">Add new genre</a>
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
                <td><a href="<?= $route('genre.detail', ['id' => $genre->id]); ?>">Details</a></td>
                <td><a href="<?= $route('genre.edit', ['id' => $genre->id]); ?>">Edit</a></td>
                <td><a href="<?= $route('genre.delete', ['id' => $genre->id]); ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<div>
    <a href="<?= $route('home'); ?>">Go back home</a>
</div>
