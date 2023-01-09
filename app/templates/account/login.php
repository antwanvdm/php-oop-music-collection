<?php
/**
 * @var string|bool $email
 * @var string $location
 * @var callable $route
 * @var callable $yield
 * @var callable $t
 */
?>
<?= $yield('partials/header'); ?>
<?= $yield('partials/errors'); ?>

<section class="columns">
    <form class="column is-6" action="<?= $route('user.login.post'); ?>" method="post" enctype="multipart/form-data">
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="email"><?= $t('account.login.emailLabel'); ?></label>
            </div>
            <div class="field-body">
                <input class="input" id="email" type="email" name="email"
                       value="<?= ($email !== false ? $email : ''); ?>"/>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="password"><?= $t('account.login.passwordLabel'); ?></label>
            </div>
            <div class="field-body">
                <input class="input" id="password" type="password" name="password"/>
            </div>
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal"></div>
            <div class="field-body">
                <input type="hidden" name="location" value="<?= $location; ?>">
                <button class="button is-primary is-fullwidth" type="submit" name="submit"><?= $t('account.login.submitValue'); ?></button>
            </div>
        </div>
    </form>
</section>

<a class="button mt-4" href="<?= $route('home'); ?>"><?= $t('general.backHomeLink'); ?></a>
