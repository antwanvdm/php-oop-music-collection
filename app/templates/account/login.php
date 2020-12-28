<?php
/**
 * @var string $pageTitle
 * @var array $errors
 * @var string|bool $email
 * @var callable $route
 */
?>
<h1><?= $pageTitle; ?></h1>
<?php if (isset($errors) && !empty($errors)) { ?>
    <ul class="errors">
        <?php for ($i = 0; $i < count($errors); $i++) { ?>
            <li><?= $errors[$i]; ?></li>
        <?php } ?>
    </ul>
<?php } ?>

<form id="login" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">
    <div>
        <label for="email"><?= $this->t->account->login->emailLabel; ?></label>
        <input type="email" name="email" id="email" value="<?= ($email !== false ? $email : ''); ?>"/>
    </div>
    <div>
        <label for="password"><?= $this->t->account->login->passwordLabel; ?></label>
        <input type="password" name="password" id="password"/>
    </div>
    <div>
        <input type="submit" name="submit" value="<?= $this->t->account->login->submitValue; ?>"/>
    </div>
</form>
<div>
    <a href="<?= $route('home'); ?>"><?= $this->t->account->login->backHomeLink; ?></a>
</div>
