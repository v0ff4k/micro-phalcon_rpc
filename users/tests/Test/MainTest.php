<?php

namespace Test;

use Phalcon\Di;
use Phalcon\Loader;

error_reporting(E_ALL);
//ini_set('display_errors', 1);
//
//define('ROOT_PATH', realpath(__DIR__ . '../..'));
//define('TESTS_PATH', ROOT_PATH . '/tests');
//defined('APP__PATH') || define('APP__PATH', ROOT_PATH . '/users');
//define('IS_CLI', false);
$config = include __DIR__.'/../../app/config/config.php';

//including /var/www/phalcon-start/tests: + EX: include_path=".:/phpfpm/includes"
set_include_path(TESTS_PATH . PATH_SEPARATOR . get_include_path());

// Required for phalcon/incubator
include ROOT_PATH . '/vendor/autoload.php';

// Use the application autoloader to autoload the classes
// Autoload the dependencies found in composer
$loader = new Loader();
$loader->registerDirs([TESTS_PATH]);
$loader->register();

// Add any needed services to the DI here
$di = require_once APP__PATH . '/bootstrap.phpfpm';
Di::setDefault($di);