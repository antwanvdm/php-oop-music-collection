<?php
/**
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */

?>
<?= $yield('partials/header'); ?>
<p class="content"><?= $t('notfound.text'); ?></p>
<a class="button mt-4" href="<?= $route('home'); ?>"><?= $t('general.backHomeLink'); ?></a>
