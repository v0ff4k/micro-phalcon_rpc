<?php

namespace app\plugins;

use \Phalcon\Mvc\View\Engine\Volt;

/**
 * Class VoltDump
 *
 * Extend default VoltEngine with soft config
 * @package app\plugins
 */
class VoltDump extends Volt
{

    public function __construct(\Phalcon\Mvc\ViewBaseInterface $view, \Phalcon\DiInterface $di){
        parent::__construct($view,$di);

        if(class_exists('\Symfony\Component\VarDumper\VarDumper')) {
            $this->getCompiler()->addFunction(
                'dump',
                function (...$params) {
                    $params = ($params) ? (( count($params) <= 1 ) ? $params[0] : $params) : '';

                    return \Symfony\Component\VarDumper\VarDumper::dump($params);
                }
            );

        }

        $config = Config::factory('config.default.php')->toArray();
//set internal config
        $this->setOptions([
            'compiledPath' => $config['application']['cacheDir'] ?? 'cache/',
            'compiledExtension' => '.compiled',
            'lifetime' => 86400,
            'compileAlways' => true,
            'compiledSeparator' => '_'
        ]);

    }

}