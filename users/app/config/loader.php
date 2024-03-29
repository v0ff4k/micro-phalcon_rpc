<?php

use Phalcon\Loader;

/**
 * Autoloader.
 *
 * This component helps to load your project classes
 * automatically based on some conventions
 * @see https://github.com/phalcon/incubator#autoloading-from-the-incubator
 * @var Phalcon\Config $config
 */

//configurable dirs and namespaces
$dirs = [
    $config->application->controllersDir,
    $config->application->pluginsDir,
    $config->application->modelsDir,
];
$namespaces = [
    'App\\Controllers' => $config->application->controllersDir,
    'App\\Models' => $config->application->modelsDir,
    'App\\Plugins' => $config->application->pluginsDir,
    '\\UnitTest'=> realpath(__DIR__.'/../tests'),
    'Phalcon' => realpath($config->application->modelsDir.'../../vendor/phalcon/incubator/Library/Phalcon/'),
];
//end of configurable namespaces

$loader = (new Loader())
    ->registerDirs($dirs)
    ->registerNamespaces($namespaces)
    ->register();