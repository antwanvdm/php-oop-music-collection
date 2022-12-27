<?php
/**
 * @var \MusicCollection\Databases\Objects\Album $album
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */

?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<a class="button is-danger mt-4" href="<?= $route('album.delete', ['id' => $album->id]); ?>?continue"><?= $t('album.delete.verifyLink'); ?></a>
<a class="button mt-4" href="<?= $route('album.index'); ?>"><?= $t('album.delete.denyLink'); ?></a>
