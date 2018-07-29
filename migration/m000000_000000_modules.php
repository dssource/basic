<?php

use yii\db\Migration;
class m000000_000000_modules extends Migration
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

        //
        $this->insert($this->tableName,[
            'id' => 1,
            'name' => 'site',
            'class' => 'dssource\basic\core\SiteModule',
            'position' => 99,
            'options' => '',
            'label' => 'Модуль сайта: темы, разделы, страницы',
            'icon' => 'glyphicon glyphicon-folder-open',
            'is_active' => 1
        ]);

        $this->insert($this->tableName,[
            'id' => 2,
            'name' => 'user',
            'class' => 'dssource\basic\core\UserModule',
            'position' => 2,
            'options' => 'strategy=fast',
            'label' => 'Модуль пользователей + права',
            'icon' => 'glyphicon glyphicon-user',
            'is_active' => 1
        ]);

        $this->insert($this->tableName,[
            'id' => 3,
            'name' => 'admin',
            'class' => 'dssource\basic\core\AdminModule',
            'position' => 2,
            'options' => '',
            'label' => 'Модуль пользователей + права',
            'icon' => 'glyphicon glyphicon-certificate',
            'is_active' => 1
        ]);

    }
    public function down()
    {
        $this->truncateTable($this->tableName);
        $this->dropTable($this->tableName);
    }
}