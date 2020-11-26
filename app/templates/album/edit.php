<?php
/**
 * @var $errors array
 * @var $success string|boolean
 * @var $album \System\Databases\Objects\Album
 * @var $albumGenreIds array
 * @var $artists \System\Databases\Objects\Artist[]
 * @var $genres \System\Databases\Objects\Genre[]
 */
?>
<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?= $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($success !== false) { ?>
    <p class="success"><?= $success; ?></p>
<?php } ?>

<?php if ($album->id !== null): ?>
    <h1>Edit "<?= $album->artist->name . ' - ' . $album->name; ?>"</h1>
    <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
        <div class="data-field">
            <label for="artist">Artist</label>
            <select id="artist" name="artist">
                <?php foreach ($artists as $artist): ?>
                    <option value="<?= $artist->id; ?>" <?= $album->artist->id === $artist->id ? 'selected' : ''; ?>><?= $artist->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="data-field">
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="<?= $album->name; ?>"/>
        </div>
        <div class="data-field">
            <span class="label">Genre</span>
            <ul class="genre-form-list">
                <?php foreach ($genres as $genre): ?>
                    <li>
                        <input type="checkbox" name="genre[]" id="genre-<?= $genre->id; ?>" value="<?= $genre->id; ?>" <?= in_array($genre->id, $albumGenreIds) ? 'checked' : ''; ?>/>
                        <label for="genre-<?= $genre->id; ?>"><?= $genre->name; ?></label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="data-field">
            <label for="year">Year</label>
            <input id="year" type="text" name="year" value="<?= $album->year; ?>"/>
        </div>
        <div class="data-field">
            <label for="tracks">Tracks</label>
            <input id="tracks" type="number" name="tracks" value="<?= $album->tracks; ?>"/>
        </div>
        <div class="data-field">
            <label for="image">Image</label>
            <input type="file" name="image" id="image"/>
        </div>
        <div class="data-submit">
            <input type="submit" name="submit" value="Save"/>
        </div>
    </form>
<?php endif; ?>
<div>
    <a href="<?= BASE_PATH; ?>albums">Go back to the list</a>
</div>
