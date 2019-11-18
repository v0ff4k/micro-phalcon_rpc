<?php

use Phalcon\Db\Column as Column;
use Phalcon\Db\Index as Index;
use \Phalcon\Mvc\Model\Migration;

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
                    new Column(
                        'login_attempts',
                        [
                            'type'          => Column::TYPE_INTEGER,
                            'size'          => 10,
                            'unsigned'      => true,
                            'notNull'       => false,
                            'after'         => 'password',
                        ]
                    ),
                    new Column(
                        'created',
                        [
                            'type'          => Column::TYPE_DATETIME,
                            'size'          => 10,
                            'notNull'       => false,
                            'after'         => 'login_attempts',
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
                \plugins\Password::make('admin'),
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