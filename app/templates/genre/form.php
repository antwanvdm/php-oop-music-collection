<?php
/**
 * @var \MusicCollection\Databases\Objects\Genre $genre
 * @var callable $route
 * @var callable $t
 */
?>
<section class="columns">
    <form class="column is-6" action="<?= $route('genres.save') ?>" method="post" enctype="multipart/form-data">
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="name"><?= $t('genre.form.nameLabel'); ?></label>
            </div>
            <div class="field-body">
                <input class="input" id="name" type="text" name="name" value="<?= $genre->name; ?>"/>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal"></div>
            <div class="field-body">
                <input type="hidden" name="id" value="<?= $genre->id; ?>"/>
                <button class="button is-primary is-fullwidth" type="submit" name="submit"><?= $t('genre.form.submitValue'); ?></button>
            </div>
        </div>
    </form>
</section>
