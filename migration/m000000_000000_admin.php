<?php

use yii\db\Migration;
class m000000_000000_admin extends Migration
{
    public $tableName = 'ds_modules';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique(),
            'class' => $this->text(),
            'position' => $this->text(),
            'options' => $this->text(),
            'label' => $this->text(),
            'icon' => $this->text(),
            'is_active' => $this->integer()->notNull()->defaultValue(1),
        ]);

        $this->insert($this->tableName,[
            'id' => 1,
            'name' => 'user',
            'class' => 'dssource\user\Module',
            'position' => 9,
            'options' => 'strategy=fast',
            'label' => 'Пользователи',
            'icon' => 'glyphicon glyphicon-user',
            'is_active' => 1
        ]);

    }
    public function down()
    {
        $this->truncateTable($this->tableName);
        $this->dropTable($this->tableName);
    }
}