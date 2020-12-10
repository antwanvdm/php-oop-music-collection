<?php
/**
 * @var array $errors
 * @var string|boolean $success
 * @var \System\Databases\Objects\Artist $artist
 * @var callable $route
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

<?php if ($artist->id !== null): ?>
    <h1>Edit "<?= $artist->name; ?>"</h1>
    <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
        <div class="data-field">
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="<?= $artist->name; ?>"/>
        </div>
        <div class="data-submit">
            <input type="submit" name="submit" value="Save"/>
        </div>
    </form>
<?php endif; ?>
<div>
    <a href="<?= $route('artist.index'); ?>">Go back to the list</a>
</div>
