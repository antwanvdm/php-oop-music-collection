<?php
/**
 * @var \System\Databases\Objects\Artist $artist
 */
?>
<form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
    <div class="data-field">
        <label for="name"><?= $this->t->artist->form->nameLabel; ?></label>
        <input id="name" type="text" name="name" value="<?= $artist->name; ?>"/>
    </div>
    <div class="data-submit">
        <input type="submit" name="submit" value="<?= $this->t->artist->form->submitValue; ?>"/>
    </div>
</form>
