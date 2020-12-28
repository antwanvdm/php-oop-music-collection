<?php
/**
 * @var string $pageTitle
 * @var callable $route
 */
?>
<h1><?= $pageTitle; ?></h1>
<p><?= $this->t->notfound->text; ?></p>
<div>
    <a href="<?= $route('home'); ?>"><?= $this->t->notfound->backHomeLink; ?></a>
</div>
