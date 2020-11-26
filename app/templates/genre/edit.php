<?php
/**
 * @var $errors array
 * @var $success string|boolean
 * @var $genre \System\Databases\Objects\Genre
 */
?>
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

<?php if ($genre->id !== null): ?>
    <h1>Edit "<?= $genre->name; ?>"</h1>
    <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
        <div class="data-field">
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="<?= $genre->name; ?>"/>
        </div>
        <div class="data-submit">
            <input type="submit" name="submit" value="Save"/>
        </div>
    </form>
<?php endif; ?>
<div>
    <a href="<?= BASE_PATH; ?>genres">Go back to the list</a>
</div>
