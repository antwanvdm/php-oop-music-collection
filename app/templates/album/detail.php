<?php
/**
 * @var \System\Databases\Objects\Album $album
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

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

