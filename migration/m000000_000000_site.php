<?php

use yii\db\Migration;
class m000000_000000_site extends Migration
{
    public $tableName = 'ds_modules';

    public function up()
    {
        // Вставка модуля в ранее загруженные компоненты modulemanager, admin
        $this->insert($this->tableName,[
            'name' => 'site',
            'class' => 'dssource\site\Module',
            'position' => 9,
            'options' => '',
            'label' => 'Сайт',
            'icon' => 'glyphicon glyphicon-plus',
            'is_active' => 1
        ]);
    }
    public function down()
    {
        //$this->truncateTable($this->tableName);
        //$this->dropTable($this->tableName);
    }
}