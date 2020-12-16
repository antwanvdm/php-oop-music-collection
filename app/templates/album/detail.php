<?php
/**
 * @var array $errors
 * @var \System\Databases\Objects\Album $album
 * @var callable $route
 */
?>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($album): ?>
    <h1><?= $album->artist->name . ' - ' . $album->name; ?></h1>

    <div>
        <img src="<?= RESOURCES_PATH . $album->image; ?>" alt="<?= $album->name; ?>"/>
    </div>
    <ul>
        <li>Genres:
            <ul>
                <?php foreach ($album->genres as $genre): ?>
                    <li><?= $genre->name; ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li>Year: <?= $album->year; ?></li>
        <li>Tracks: <?= $album->tracks; ?></li>
    </ul>
<?php endif; ?>

<div>
    <a href="<?= $route('album.index'); ?>">Go back to the list</a>
</div>

