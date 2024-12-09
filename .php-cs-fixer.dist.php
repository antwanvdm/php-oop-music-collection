<?php
$finder = PhpCsFixer\Finder::create()
    ->exclude(['vendor', '_tmp'])
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_after_opening_tag' => false,
    'blank_lines_before_namespace' => false,
    'no_blank_lines_after_phpdoc' => true,
    'no_extra_blank_lines' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'single_quote' => true,
    'ternary_to_null_coalescing' => true,
])->setFinder($finder);
