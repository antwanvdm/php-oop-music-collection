<?php
/**
 * @var string $pageTitle
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

<?php if ($genre): ?>
    <h1><?= $pageTitle ; ?></h1>
    <ul>
        <li><?= $this->t->genre->detail->albumLabel; ?>
            <ul>
                <?php foreach ($genre->albums as $album): ?>
                    <li><?= $album->name; ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
<?php endif; ?>

<div>
    <a href="<?= $route('genre.index'); ?>"><?= $this->t->genre->backToListLink; ?></a>
</div>

