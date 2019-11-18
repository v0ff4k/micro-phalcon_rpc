<?php

namespace app\plugins;

use Phalcon\Http\Request;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

/**
 * <pre><code class="language-php">
 * $logger = new Logger(); //デフォルトのパス、ファイル名
 *
 * $logger = new Logger('/log/path'); //任意のパス、デフォルトのファイル名
 *
 * $logger = new Logger('/log/path', 'logname.log'); //任意のパス、ファイル名
 *
 * $logger = new Logger(null, 'logname.log'); //デフォルトのパス、任意のファイル名
 * </code></pre>
 *
 * <pre><code class="language-php">
 * $logger->info('message');
 * $logger->notice('message');
 * $logger->warning('message');
 * $logger->error('message');
 * $logger->debug('message');
 * </code></pre>
 *
 * <pre><code class="language-php">
 * $logger->write(['msg1', 'msg2'], Logger::INFO);
 * $logger->write([
 *     　　　['msg' => 'msg1'],
 *          ['msg' => 'msg2'],
 *      ], Logger::INFO);
 *
 * $logger->write([
 *          ['msg' => 'msg1', 'level' => Logger::INFO],
 *          ['msg' => 'msg2', 'level' => Logger::NOTICE],
 *      ]);
 * </code></pre>
 *
 * @author muramoya
 * @version: 1.0.2
 */
class Logger
{
    /**
     * @var FileAdapter
     */
    private $logger;

    const INFO = 'info';
    const NOTICE = 'notice';
    const WARNING = 'warning';
    const ERROR = 'error';
    const DEBUG = 'debug';

    /**
     * constructor.
     *
     * @param string $filePath
     * @param string $fileName
     * @throws \Exception
     */
    public function __construct($filePath = null, $fileName = null)
    {
        if (is_null($filePath) || is_null($fileName)) {
            $conf = Config::factory('config.default.php');
        }

        $dir = is_null($filePath) ? $conf->logger->path : $filePath;
        $file = is_null($fileName) ? $conf->logger->filename : $fileName;

        if(false === realpath($dir)) throw new \Exception($dir . ': no such dir.');

        $logPath = realpath($dir) . '/' . $file;

        $url = (new Request())->getURI();
        $format = str_replace('%url%', $url, $conf->logger->format);

        $formatter = new LineFormatter($format, $conf->logger->date);
        $this->logger = new FileAdapter($logPath);

        $this->logger->setFormatter($formatter);
    }

    /**
     * 複数のログを書き込みます
     * @param array $contents
     * @param null $level
     */
    public function write(array $contents, $level = null) {
        $this->logger->begin();

        foreach ($contents as $content) {
            if (!is_array($content) || !isset($content['level'])) {
                if (is_null($level)) {
                    $this->warning('No log level set');
                    $this->info($content);
                } else {
                    $this->$level($content);
                }
            } else {
                $level = $content['level'];
                $this->$level($content['msg']);
            }
        }
        $this->logger->commit();
    }

    /**
     * infoレベルのログを1つ書き込みます
     * @param string $msg
     */
    public function info($msg)
    {
        $this->logger->info($msg);
    }

    /**
     * noticeレベルのログを1つ書き込みます
     * @param string $msg
     */
    public function notice($msg)
    {
        $this->logger->notice($msg);
    }

    /**
     * warningレベルのログを1つ書き込みます
     * @param string $msg
     */
    public function warning($msg)
    {
        $this->logger->warning($msg);
    }

    /**
     * errorレベルのログを1つ書き込みます
     * @param string $msg
     */
    public function error($msg)
    {
        $this->logger->error($msg);
    }

    /**
     * debugレベルのログを1つ書き込みます
     * @param string $msg
     */
    public function debug($msg)
    {
        $this->logger->debug($msg);
    }
}