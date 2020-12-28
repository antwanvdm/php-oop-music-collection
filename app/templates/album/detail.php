<?php
/**
 * @var string $pageTitle
 * @var array $errors
 * @var \System\Databases\Objects\Album $album
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

<?php if ($album): ?>
    <div>
        <img src="<?= RESOURCES_PATH . $album->image; ?>" alt="<?= $album->name; ?>"/>
    </div>
    <ul>
        <li><?= $this->t->album->detail->genreLabel; ?>
            <ul>
                <?php foreach ($album->genres as $genre): ?>
                    <li><?= $genre->name; ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li><?= $this->t->album->detail->yearLabel; ?> <?= $album->year; ?></li>
        <li><?= $this->t->album->detail->tracksLabel; ?> <?= $album->tracks; ?></li>
    </ul>
<?php endif; ?>

<div>
    <a href="<?= $route('album.index'); ?>"><?= $this->t->album->backToListLink; ?></a>
</div>

