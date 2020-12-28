<?php
/**
 * @var string $pageTitle
 * @var array $errors
 * @var string|boolean $success
 * @var \System\Databases\Objects\Artist $artist
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
        <label for="name"><?= $this->t->artist->form->nameLabel; ?></label>
        <input id="name" type="text" name="name" value="<?= $artist->name; ?>"/>
    </div>
    <div class="data-submit">
        <input type="submit" name="submit" value="<?= $this->t->artist->form->submitValue; ?>"/>
    </div>
</form>
<div>
    <a href="<?= $route('artist.index'); ?>"><?= $this->t->artist->backToListLink; ?></a>
    <a href="<?= $route('account.logout'); ?>"><?= $this->t->artist->add->logoutLink; ?></a>
</div>
