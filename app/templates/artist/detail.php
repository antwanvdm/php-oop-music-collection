<?php
/**
 * @var string $pageTitle
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

<?php if ($artist): ?>
    <h1><?= $pageTitle; ?></h1>
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

