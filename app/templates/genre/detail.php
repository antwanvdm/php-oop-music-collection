<?php
/**
 * @var $errors array
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
    <a href="<?= BASE_PATH; ?>genres">Go back to the list</a>
</div>

