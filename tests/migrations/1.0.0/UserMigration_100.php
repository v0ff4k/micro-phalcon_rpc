<?php

use Phalcon\Db\Column as Column;
use Phalcon\Db\Index as Index;
use Phalcon\Mvc\Model\Migration;

class UserMigration_100 extends Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        $this->morphTable(
            'users',
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type'          => Column::TYPE_INTEGER,
                            'size'          => 10,
                            'unsigned'      => true,
                            'notNull'       => true,
                            'autoIncrement' => true,
                            'first'         => true,
                        ]
                    ),
                    new Column(
                        'login',
                        [
                            'type'    => Column::TYPE_VARCHAR,
                            'size'    => 32,
                            'notNull' => true,
                            'after'   => 'id',
                        ]
                    ),
                    new Column(
                        'password',
                        [
                            'type'    => Column::TYPE_VARCHAR,
                            'size'    => 64,
                            'notNull' => true,
                            'after'   => 'login',
                        ]
                    ),
                ],
                'indexes' => [
                    new Index(
                        'PRIMARY',
                        [
                            'id',
                        ]
                    ),
                ],
            ]
        );

        self::$_connection->insert(
            'users',
            [
                1,
                'admin',
                '21232f297a57a5a743894a0e4a801fc3',
            ],
            [
                'id',
                'login',
                'password',
            ]
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        // in case revercing, add something here
    }
}