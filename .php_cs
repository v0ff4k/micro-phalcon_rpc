<?php

$fileHeaderComment = <<<COMMENT
dist config for cs fixer. tool to fix code to follow standards in DEV env.
usage:
 php php-cs-fixer.phar fix /path/to/dir
COMMENT;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('site/vendor')
    ->exclude('users/vendor')
    ->notPath('db/test.db')
;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'linebreak_after_opening_tag' => true,
        'mb_str_functions' => true,
        'no_php4_constructor' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'php_unit_strict' => true,
        'phpdoc_order' => true,
        'semicolon_after_instruction' => true,
        'strict_comparison' => true,
        'strict_param' => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/.php_cs.cache')
;
