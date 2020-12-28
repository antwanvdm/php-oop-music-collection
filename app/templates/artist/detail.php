<?php
/**
 * @var \System\Databases\Objects\Artist $artist
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<?php if ($artist): ?>
    <ul>
        <li><?= $this->t->artist->detail->albumLabel; ?>
            <ul>
                <?php foreach ($artist->albums as $album): ?>
                    <li><?= $album->name; ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
<?php endif; ?>

<div>
    <a href="<?= $route('artist.index'); ?>"><?= $this->t->artist->backToListLink; ?></a>
</div>

