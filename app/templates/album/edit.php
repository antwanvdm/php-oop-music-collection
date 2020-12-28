<?php
/**
 * @var string $pageTitle
 * @var array $errors
 * @var string|boolean $success
 * @var \System\Databases\Objects\Album $album
 * @var array $albumGenreIds
 * @var \System\Databases\Objects\Artist[] $artists
 * @var \System\Databases\Objects\Genre[] $genres
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

<?php if ($success !== false) { ?>
    <p class="success"><?= $success; ?></p>
<?php } ?>

<?php if ($album->id !== null): ?>
    <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
        <div class="data-field">
            <label for="artist"><?= $this->t->album->form->artistLabel; ?></label>
            <select id="artist" name="artist">
                <?php foreach ($artists as $artist): ?>
                    <option value="<?= $artist->id; ?>" <?= $album->artist->id === $artist->id ? 'selected' : ''; ?>><?= $artist->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="data-field">
            <label for="name"><?= $this->t->album->form->nameLabel; ?></label>
            <input id="name" type="text" name="name" value="<?= $album->name; ?>"/>
        </div>
        <div class="data-field">
            <span class="label"><?= $this->t->album->form->genreLabel; ?></span>
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
            <label for="year"><?= $this->t->album->form->yearLabel; ?></label>
            <input id="year" type="text" name="year" value="<?= $album->year; ?>"/>
        </div>
        <div class="data-field">
            <label for="tracks"><?= $this->t->album->form->tracksLabel; ?></label>
            <input id="tracks" type="number" name="tracks" value="<?= $album->tracks; ?>"/>
        </div>
        <div class="data-field">
            <label for="image"><?= $this->t->album->form->imageLabel; ?></label>
            <input type="file" name="image" id="image"/>
        </div>
        <div class="data-submit">
            <input type="submit" name="submit" value="<?= $this->t->album->form->submitValue; ?>"/>
        </div>
    </form>
<?php endif; ?>
<div>
    <a href="<?= $route('album.index'); ?>"><?= $this->t->album->backToListLink; ?></a>
</div>
