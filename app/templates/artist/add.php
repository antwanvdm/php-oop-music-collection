<?php
/**
 * @var array $errors
 * @var string|boolean $success
 * @var \System\Databases\Objects\Artist $artist
 * @var callable $route
 */
?>
<h1>Add artist</h1>
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
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="<?= $artist->name; ?>"/>
    </div>
    <div class="data-submit">
        <input type="submit" name="submit" value="Save"/>
    </div>
</form>
<div>
    <a href="<?= $route('artist.index'); ?>">Go back to the list</a>
    <a href="<?= $route('account.logout'); ?>">Logout</a>
</div>
