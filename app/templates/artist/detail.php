<?php
/**
 * @var array $errors
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

<?php if (isset($artist)): ?>
    <h1><?= $artist->name ; ?></h1>
    <ul>
        <li>Albums:
            <ul>
                <?php foreach ($artist->albums as $album): ?>
                    <li><?= $album->name; ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
<?php endif; ?>

<div>
    <a href="<?= $route('artist.index'); ?>">Go back to the list</a>
</div>

