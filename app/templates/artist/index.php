<?php
/**
 * @var $errors array
 * @var $totalArtists int
 * @var $artists \System\Databases\Objects\Artist[]
 */
?>
<h1>Artists</h1>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?= BASE_PATH; ?>artists/add">Add new artist</a>
<?php if (isset($artists) && isset($totalArtists)): ?>
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
            <td colspan="7">&copy; My Collection with <?= $totalArtists; ?> artists</td>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($artists as $artist): ?>
            <tr>
                <td><?= $artist->id; ?></td>
                <td><?= $artist->name; ?></td>
                <td><?= count($artist->albums); ?></td>
                <td><a href="<?= BASE_PATH; ?>artists/detail?id=<?= $artist->id; ?>">Details</a></td>
                <td><a href="<?= BASE_PATH; ?>artists/edit?id=<?= $artist->id; ?>">Edit</a></td>
                <td><a href="<?= BASE_PATH; ?>artists/delete?id=<?= $artist->id; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<div>
    <a href="<?= BASE_PATH; ?>">Go back home</a>
</div>
