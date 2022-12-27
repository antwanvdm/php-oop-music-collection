<?php
/**
 * Rules we follow are from PSR-2 as well as the rectified PSR-2 guide.
 *
 * - https://github.com/FriendsOfPHP/PHP-CS-Fixer
 * - https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
 * - https://github.com/php-fig-rectified/fig-rectified-standards/blob/master/PSR-2-R-coding-style-guide-additions.md
 *
 * If something isn't addressed in either of those, some other common community rules are
 * used that might not be addressed explicitly in PSR-2 in order to improve code quality
 * (so that devs don't need to comment on them in Code Reviews).
 *
 * For instance: removing trailing white space, removing extra line breaks where
 * they're not needed (back to back, beginning or end of function/class, etc.),
 * adding trailing commas in the last line of an array, etc.
 */

$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor', '_tmp'])
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_after_opening_tag' => false,
    'single_blank_line_before_namespace' => false,
    'no_blank_lines_after_phpdoc' => true,
    'no_extra_blank_lines' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'single_quote' => true,
    'ternary_to_null_coalescing' => true,
])->setFinder($finder);
