<?php

/**
 * This file only for PHP's built-in webserver.
 * Others(Nginx, Apache, Tomcat) has no mechanism to use that file.
 * @use   php -S localhost:8000 .htrouter.php
 * @see https://docs.phalcon.io/3.4/en/webserver-setup#php-built-in
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if (
    $uri !== '/' &&
    file_exists(__DIR__ . '/public' . $uri)
) {
    return false;
}

$_GET['_url'] = $_SERVER['REQUEST_URI'];

require_once __DIR__ . '/public/index.php';