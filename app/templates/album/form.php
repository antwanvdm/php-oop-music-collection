<?php
/**
 * @var \MusicCollection\Databases\Objects\Album $album
 * @var \MusicCollection\Databases\Objects\Artist[] $artists
 * @var \MusicCollection\Databases\Objects\Genre[] $genres
 * @var int[] $genreIds
 * @var callable $route
 * @var callable $t
 */
?>
<section class="columns">
    <form class="column is-6" action="<?= $route('albums.save') ?>" method="post" enctype="multipart/form-data">
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="artist-id"><?= $t('album.form.artistLabel'); ?></label>
            </div>
            <div class="field-body select is-fullwidth">
                <select name="artist-id" id="artist-id">
                    <?php foreach ($artists as $artist): ?>
                        <option value="<?= $artist->id; ?>" <?= $artist->id === $album->artist_id ? 'selected' : '' ?>><?= $artist->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="name"><?= $t('album.form.nameLabel'); ?></label>
            </div>
            <div class="field-body">
                <input class="input" id="name" type="text" name="name" value="<?= $album->name; ?>"/>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="genre-ids"><?= $t('album.form.genreLabel'); ?></label>
            </div>
            <div class="field-body select is-multiple is-fullwidth">
                <select multiple size="3" name="genre-ids[]" id="genre-ids" title="Genres">
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?= $genre->id; ?>" <?= in_array($genre->id, $genreIds) ? 'selected' : '' ?>><?= $genre->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="year"><?= $t('album.form.yearLabel'); ?></label>
            </div>
            <div class="field-body">
                <input class="input" id="year" type="text" name="year" value="<?= $album->year; ?>"/>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="tracks"><?= $t('album.form.tracksLabel'); ?></label>
            </div>
            <div class="field-body">
                <input class="input" id="tracks" type="number" name="tracks" value="<?= $album->tracks; ?>"/>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="image"><?= $t('album.form.imageLabel'); ?></label>
            </div>
            <div class="field-body">
                <input class="input" id="image" type="file" name="image"/>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal"></div>
            <div class="field-body">
                <input type="hidden" name="id" value="<?= $album->id; ?>"/>
                <input type="hidden" name="current-image" value="<?= $album->image; ?>"/>
                <button class="button is-primary is-fullwidth" type="submit" name="submit"><?= $t('album.form.submitValue'); ?></button>
            </div>
        </div>
    </form>
</section>
