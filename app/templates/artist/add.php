<?php
/**
 * @var $errors array
 * @var $success string|boolean
 * @var $artist \System\Databases\Objects\Artist
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
    <a href="<?= BASE_PATH; ?>artists">Go back to the list</a>
    <a href="<?= BASE_PATH; ?>user/logout">Logout</a>
</div>
