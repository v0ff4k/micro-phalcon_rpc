<?php

namespace app\plugins;

use Phalcon\Config\Adapter\Php as ConfigPhp;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Config\Adapter\Yaml as ConfigYml;

/**
 * Class Config
 * File to config factory.
 *
 * <pre><code class="language-php">
 * //confディレクトリ以下のファイルを読み込む方法
 * $conf = Config::factory('app.php'); //ファイル名のみを指定します。
 * $line = $conf->foo; //Phalconでは読み込んだ設定ファイルをプロパティ指定で取得できます。
 *
 * //パスを指定する方法
 * $conf = Config::factory('/hoge/bar/piyo.php');
 * $line = $conf->foo;
 * </code></pre>
 *
 * @author muramoya
 * @version: 1.2(updated by 9r00+)
 * @package app\plugins
 */
class Config
{

    /**
     * ファクトリメソッド
     * @param $file
     * @return bool|\Phalcon\Config
     */
    public static function factory($file) {
        if (strpos($file, '/') === false) {
            $file = realpath(APP_PATH.'/config') . '/' . $file;
        }

        if (!file_exists($file)) return false;

        $explodePath = explode('/', $file);
        $fileName = end($explodePath);

        $explodeExt = explode('.', $fileName);

        switch (end($explodeExt)) {
            case 'ini':
                return new ConfigIni($file);
            case 'php':
                return new ConfigPhp($file);
            case 'yml':
            case 'yaml':
                try {
                    return new ConfigYml($file);
                } catch (\Phalcon\Config\Exception $e) {
                    (new Logger())->warning(
                        'Error: ' . $e->getMessage() .
                        ', yaml: ' . $file .
                        ', trace: ' . $e->getTraceAsString()
                    );
                    return new \Phalcon\Config();
                }
            default:
                return false;
        }
    }
}
