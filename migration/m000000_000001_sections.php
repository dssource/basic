<?php

use yii\db\Migration;

class m000000_000001_sections extends Migration
{
    public $tableName = 'ds_site_sections';

    public function up()
    {
       $this->createTable($this->tableName,[
           'id' => $this->primaryKey(),
           'id_parent' => $this->integer(),
           'image' => $this->text(),
           'name' => $this->string(60)->notNull()->unique(),
           'alias' => $this->string(60)->notNull()->unique(),
           'class' => $this->text(),
           'description' => $this->text(),
           'show_in_catalog' => $this->boolean(),
           'default_item_id' => $this->integer(),
           'options' => $this->text(),
       ]);

        $this->insert($this->tableName,[
            'id' => 1,
            'id_parent' => 0,
            'name' => 'Корневые страницы',
            'alias' => 'index',
            'class' => 'dssource\basic\models\Section',
            'description' => 'Системный каталог',
            'show_in_catalog' => false,
            'default_item_id' => 1,
            'options' => '',
        ]);
    }
    public function down()
    {
        $this->truncateTable($this->tableName);
        $this->dropTable($this->tableName);
    }
}