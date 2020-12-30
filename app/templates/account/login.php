<?php
/**
 * @var string|bool $email
 * @var string $location
 * @var callable $route
 * @var callable $yield
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<form id="login" method="post" action="<?= $route('account.login.post'); ?>">
    <div>
        <label for="email"><?= $this->t->account->login->emailLabel; ?></label>
        <input type="email" name="email" id="email" value="<?= ($email !== false ? $email : ''); ?>"/>
    </div>
    <div>
        <label for="password"><?= $this->t->account->login->passwordLabel; ?></label>
        <input type="password" name="password" id="password"/>
    </div>
    <div>
        <input type="hidden" name="location" value="<?= $location; ?>">
        <input type="submit" name="submit" value="<?= $this->t->account->login->submitValue; ?>"/>
    </div>
</form>
<div>
    <a href="<?= $route('home'); ?>"><?= $this->t->account->login->backHomeLink; ?></a>
</div>
