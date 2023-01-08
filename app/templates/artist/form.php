<?php
/**
 * @var \MusicCollection\Databases\Objects\Artist $artist
 * @var callable $route
 * @var callable $t
 */
?>
<section class="columns">
    <form class="column is-6" action="<?= $route('artists.save'); ?>" method="post" enctype="multipart/form-data">
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="name"><?= $t('artist.form.nameLabel'); ?></label>
            </div>
            <div class="field-body">
                <input class="input" id="name" type="text" name="name" value="<?= $artist->name; ?>"/>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal"></div>
            <div class="field-body">
                <input type="hidden" name="id" value="<?= $artist->id; ?>"/>
                <button class="button is-primary is-fullwidth" type="submit" name="submit"><?= $t('artist.form.submitValue'); ?></button>
            </div>
        </div>
    </form>
</section>