<?php

use yii\db\Migration;

class m000000_000002_pages extends Migration
{
    public $tableName = 'ds_site_pages';

    public function up()
    {
       $this->createTable($this->tableName,[
           'id' => $this->primaryKey(),
           'image' => $this->text(),
           'name' => $this->string(90)->notNull()->unique(),
           'alias' => $this->string(90)->notNull()->unique(),
           'short' => $this->text(),
           'content' => $this->text(),
           'is_publish' => $this->boolean(),
           'vk_import' => $this->text(),
           'description' => $this->text(),
           'keywords' => $this->text(),
           'id_section' => $this->integer(),
           'create_at' => $this->integer(),
           'update_at' => $this->integer(),
           'class' => $this->text()
       ]);

        $this->insert($this->tableName,[
            'id' => 1,
            'image' => 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjUxMiIgaGVpZ2h0PSI1MTIiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48dGl0bGU+PC90aXRsZT48ZyBpZD0iaWNvbW9vbi1pZ25vcmUiPjwvZz48cGF0aCBkPSJNNTEyIDMwNGwtOTYtOTZ2LTE0NGgtNjR2ODBsLTk2LTk2LTI1NiAyNTZ2MTZoNjR2MTYwaDE2MHYtOTZoNjR2OTZoMTYwdi0xNjBoNjR6Ij48L3BhdGg+PC9zdmc+',
            'name' => 'Главная страница',
            'alias' => 'index',
            'short' => 'Главная страница',
            'content' => 'Главная страница',
            'is_publish' => true,
            'vk_import' => '',
            'description' => 'Описание страницы',
            'keywords' => "Ключевые слова, через запятую",
            'id_section' => 1,
            'create_at' => time(),
            'update_at' => time(),
            'class' => 'dssource\site\models\Page'

        ]);
    }
    public function down()
    {
        $this->truncateTable($this->tableName);
        $this->dropTable($this->tableName);
    }
}