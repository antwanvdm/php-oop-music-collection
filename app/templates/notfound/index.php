<?php
/**
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<p><?= $this->t->notfound->text; ?></p>
<div>
    <a href="<?= $route('home'); ?>"><?= $this->t->notfound->backHomeLink; ?></a>
</div>
