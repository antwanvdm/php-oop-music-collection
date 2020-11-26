<?php
/**
 * @var $errors array
 * @var $album \System\Databases\Objects\Album
 */
?>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (isset($album)): ?>
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
    <a href="<?= BASE_PATH; ?>albums">Go back to the list</a>
</div>

