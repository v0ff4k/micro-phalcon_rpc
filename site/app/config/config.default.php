<?php

return [
    'application' => [
        'title' => 'Simple Docker + Phalcon app',
        'description' => 'Web on front(site) and  closed JsonRpc back(users)',
        'baseUri' => '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'cacheDir'       => APP_PATH . '/cache/',
        'pluginsDir' => APP_PATH . '/plugins/',
        'viewsDir' => APP_PATH . '/views/',
//other variations, unused.
//        'libraryDir' => APP_PATH . '/library/',
//        'modelsDir' => APP_PATH . '/models/',
//        'migrationsDir' => APP_PATH . '/migrations/',
//        'middlewaresDir' => APP_PATH . '/middlewares/',
    ],
    'logger' => [
        'path'     => APP_PATH . '/logs/',
        'format'   => '[%date%] [%type%] [%url%] %message%',
        'date'     => 'D j H:i:s',
        'logLevel' => ('dev' === APP_ENV ? 'debug' : 'warning'),
        'filename' => APP_ENV . '-'.date("Y-m-d").'.log',
    ],
];