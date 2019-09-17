<?php

namespace Test;

use \UnitTestCase;
use PDO;
use Phalcon\Db\Adapter\Pdo\Sqlite as DbAdapter;
use Phalcon\Events\Manager as EventsManager;
use App\Core\Event\DbListener;

class DbTest extends UnitTestCase
{
    public $table = 'test';
    public function setUp()
    {
        parent::setUp();
        if (!DB::tableExists('test')) {
            $sql = "CREATE TABLE `{$this->table}` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `login` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
              `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
            DB::execute($sql);
        }
    }
    public function testBaseCase()
    {
        $sql = "SELECT * FROM `user`;";
        $res = DB::query($sql);
        $this->assertTrue(count($res) > 0);
    }
    public function testDb1Case()
    {
        $di = di();
        $config = di('config');
        $di->setShared('db1', function () use ($config) {
            $db = new DbAdapter(
                [
                    'host' => $config->database->host,
                    'username' => $config->database->username,
                    'password' => $config->database->password,
                    'dbname' => $config->database->dbname,
                    'charset' => $config->database->charset,
                    'options' => [
                        PDO::ATTR_CASE => PDO::CASE_NATURAL,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                        PDO::ATTR_STRINGIFY_FETCHES => false,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ],
                ]
            );
            if ($config->log->db) {
                $eventsManager = new EventsManager();
                $dbListener = new DbListener();
                $eventsManager->attach(
                    "db",
                    $dbListener
                );
                $db->setEventsManager($eventsManager);
            }
            return $db;
        });
        $sql = "SELECT * FROM `user`;";
        $res = DB::query($sql);
        $this->assertTrue(count($res) > 0);
    }
    public function testInsert()
    {
        $sql = "INSERT INTO {$this->table} (`login`,`password`) VALUES (?,?)";
        $res = DB::execute($sql, ['admin', '21232f297a57a5a743894a0e4a801fc3']);
        $this->assertTrue($res);
    }
    public function testQuery()
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `login` = ? LIMIT 1;";
        $res = DB::query($sql, ['admin']);
        $this->assertTrue(count($res) > 0);
        $this->assertTrue(is_array($res));
    }
    public function testFetch()
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `login` = ? LIMIT 1;";
        $res = DB::fetch($sql, ['admin']);
        $this->assertTrue(is_array($res));
        $res = DB::fetch($sql, ['admin'], PDO::FETCH_OBJ);
        $this->assertTrue(is_object($res));
    }
    public function testExecute()
    {
        $sql = "INSERT INTO {$this->table} (`login`,`password`) VALUES (?,?)";
        $res = DB::execute($sql, ['admin', '21232f297a57a5a743894a0e4a801fc3']);
        $this->assertTrue($res);
        $res = DB::execute($sql, ['admin', '21232f297a57a5a743894a0e4a801fc3'], true);
        $this->assertEquals(1, $res);
        $sql = "UPDATE {$this->table} SET password=? WHERE login =?";
        $res = DB::execute($sql, ['21232f297a57a5a743894a0e4a801fc3', 'admin'], true);
        $this->assertTrue(is_numeric($res));
    }
    public function testTableExist()
    {
        $this->assertTrue(DB::tableExists($this->table));
        $this->assertFalse(DB::tableExists('sss'));
    }
}


class DB implements DBInteface
{
    protected static $dbServiceName = 'db';

    public static function query($sql, $params = [], $fetchMode = PDO::FETCH_ASSOC)
    {
        return parent::query($sql, $params, $fetchMode);
    }

    public static function fetch($sql, $params = [], $fetchMode = PDO::FETCH_ASSOC)
    {
        return parent::fetch($sql, $params, $fetchMode);
    }

    public static function execute($sql, $params = [], $withRowCount = false)
    {
        return parent::execute($sql, $params, $withRowCount);
    }

    public static function execWithRowCount($sql, $params = [])
    {
        return parent::execWithRowCount($sql, $params);
    }

    public static function begin()
    {
        return parent::begin();
    }

    public static function rollback()
    {
        return parent::rollback();
    }

    public static function commit()
    {
        return parent::commit();
    }

    public static function __callStatic($name, $arguments)
    {
        $db = di(static::$dbServiceName);
        return $db->$name(...$arguments);
    }
}