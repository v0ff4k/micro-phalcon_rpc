<?php

namespace Test;

use Phalcon\Di;
use Phalcon\Loader;

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('TESTS_PATH', ROOT_PATH . '/tests');
defined('APP_PATH') || define('APP_PATH', ROOT_PATH . '/app');
define('IS_CLI', false);

set_include_path(TESTS_PATH . PATH_SEPARATOR . get_include_path());

// Required for phalcon/incubator
include __DIR__ . '/../vendor/autoload.php';

// Use the application autoloader to autoload the classes
// Autoload the dependencies found in composer
$loader = new Loader();
$loader->registerDirs([TESTS_PATH]);
$loader->register();

// Add any needed services to the DI here
$di = require_once APP_PATH . '/bootstrap.php';
Di::setDefault($di);