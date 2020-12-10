<?php
/**
 * @var array $errors
 * @var \System\Databases\Objects\Genre $genre
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

<?php if (isset($genre)): ?>
    <h1><?= $genre->name ; ?></h1>
    <ul>
        <li>Albums:
            <ul>
                <?php foreach ($genre->albums as $album): ?>
                    <li><?= $album->name; ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
<?php endif; ?>

<div>
    <a href="<?= $route('genre.index'); ?>">Go back to the list</a>
</div>

