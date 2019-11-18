<?php

use Phalcon\Config;

//pointing to  ...users/app  folder
defined('APP_PATH') || define('APP_PATH', realpath(dirname(dirname(__FILE__))));

//default we are working under development environment!
defined('APP_ENV') || define('APP_ENV', getenv('APP_ENV') ?: 'dev');


$default = include 'config.default.php';
$config = new Config ($default);

$additionalConfig = 'config.' . APP_ENV . '.php';

if(file_exists($additionalConfig)) {
    $configNew = new Config(include_once $additionalConfig);
    $config = $config->merge($configNew);
}
return $config;