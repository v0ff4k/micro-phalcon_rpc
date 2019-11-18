<?php

return [
    'application' => [
        'title' => 'Simple Docker + Phalcon app',
        'description' => 'Web on front(site) and  closed JsonRpc back(users)',
        'baseUri' => '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir' => APP_PATH . '/models/',
        'pluginsDir' => APP_PATH . '/plugins/',
//other variations, unused.
//        'libraryDir' => APP_PATH . '/library/',
//        'viewsDir' => APP_PATH . '/views/',
//        'cacheDir'       => APP_PATH . '/cache/',
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