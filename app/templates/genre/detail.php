<?php
/**
 * @var \System\Databases\Objects\Genre $genre
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($genre): ?>
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

