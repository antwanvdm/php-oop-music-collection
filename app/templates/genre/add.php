<?php
/**
 * @var string $pageTitle
 * @var array $errors
 * @var string|bool $success
 * @var \System\Databases\Objects\Genre $genre
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

<form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
    <div class="data-field">
        <label for="name"><?= $this->t->genre->form->nameLabel; ?></label>
        <input id="name" type="text" name="name" value="<?= $genre->name; ?>"/>
    </div>
    <div class="data-submit">
        <input type="submit" name="submit" value="<?= $this->t->genre->form->submitValue; ?>"/>
    </div>
</form>
<div>
    <a href="<?= $route('genre.index'); ?>"><?= $this->t->genre->backToListLink; ?></a>
</div>
