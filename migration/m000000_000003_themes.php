<?php

use yii\db\Migration;

class m000000_000003_themes extends Migration
{
    public $tableName = 'ds_themes';

    public function up()
    {
       $this->createTable($this->tableName,[
           'id' => $this->primaryKey(),
           'name' => $this->string(50)->unique(),
           'path' => $this->text(),
           'options' => $this->text(),
           'description' => $this->text(),
           'author' => $this->text(),
           'version' => $this->text(),
           'active' => $this->boolean(),

       ]);

        $this->insert($this->tableName,[
            'id' => 1,
            'name' => 'Dracula',
            'path' => 'dracula',
            'options' => 'someVariable=1;someVariable2=2',
            'description' => 'Dracula Темная тема',
            'author' => 'WEB-Studio Digital-Solution (https://digital-solution.ru)',
            'version' => '0.1',
            'active' => true
        ]);
    }
    public function down()
    {
        $this->truncateTable($this->tableName);
        $this->dropTable($this->tableName);
    }
}