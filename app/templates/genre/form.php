<?php
/**
 * @var \System\Databases\Objects\Genre $genre
 * @var callable $route
 */
?>
<form action="<?= $route('genre.save') ?>" method="post" enctype="multipart/form-data">
    <div class="data-field">
        <label for="name"><?= $this->t->genre->form->nameLabel; ?></label>
        <input id="name" type="text" name="name" value="<?= $genre->name; ?>"/>
    </div>
    <div class="data-submit">
        <input type="hidden" name="id" value="<?= $genre->id; ?>"/>
        <input type="submit" name="submit" value="<?= $this->t->genre->form->submitValue; ?>"/>
    </div>
</form>
