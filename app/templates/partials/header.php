<?php
/**
 * @var string $pageTitle
 * @var string $currentLanguage
 * @var string[] $languages
 * @var callable $route
 */
?>
<header class="my-4">
    <div class="columns">
        <div class="column is-flex-grow-5">
            <h1 class="title"><?= $pageTitle; ?></h1>
        </div>
        <div class="column">
            <form action="<?= $route('language.change'); ?>" method="post">
                <div class="select">
                    <select id="language-form-select" name="language" title="Choose Language">
                        <?php foreach ($languages as $language => $languageLabel): ?>
                            <option value="<?= $language; ?>" <?= $currentLanguage === $language ? 'selected' : '' ?>><?= $languageLabel; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
</header>
