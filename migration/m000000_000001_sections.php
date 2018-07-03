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
            'image' => 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjUxMiIgaGVpZ2h0PSI1MTIiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48dGl0bGU+PC90aXRsZT48ZyBpZD0iaWNvbW9vbi1pZ25vcmUiPjwvZz48cGF0aCBkPSJNNTEyIDMwNGwtOTYtOTZ2LTE0NGgtNjR2ODBsLTk2LTk2LTI1NiAyNTZ2MTZoNjR2MTYwaDE2MHYtOTZoNjR2OTZoMTYwdi0xNjBoNjR6Ij48L3BhdGg+PC9zdmc+',
            'name' => 'Корневые страницы',
            'alias' => 'index',
            'class' => 'dssource\site\models\Section',
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