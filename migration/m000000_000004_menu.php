<?php

use yii\db\Migration;

class m000000_000004_menu extends Migration
{
    public $tableName = 'ds_menu';

    public function up()
    {
       $this->createTable($this->tableName,[
           'id' => $this->primaryKey(),
           'label' => $this->text(),
           'url' => $this->text(),
           'accept' => $this->text(),
           'position' => $this->integer(),
           'id_parent' => $this->integer(),
           'active' => $this->boolean(),

       ]);

        $this->insert($this->tableName,[
            'id' => 1,
            'label' => 'Главная',
            'url' => '/',
            'accept' => '@',
            'position' => 1,
            'id_parent' => 0,
            'active' => true
        ]);
    }
    public function down()
    {
        $this->truncateTable($this->tableName);
        $this->dropTable($this->tableName);
    }
}