<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['url' => '/admin/menu', 'label' => 'Пункты меню'];
if($model->isNewRecord) $this->params['breadcrumbs'][] = 'Создать новый пункт';
else
    $this->params['breadcrumbs'][] = 'Изменить пункт';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
        <? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
        <?php
        echo $form->field($model, 'label');
        echo $form->field($model, 'url');
        echo $form->field($model, 'id_parent')->dropDownList($model->allItems());
        echo $form->field($model, 'accept')->dropDownList($model->acceptArray());;
        echo $form->field($model, 'position')->Input('number', [ 'min' => 1, 'max' => 10]);
        echo $form->field($model, 'active')->checkbox();

        ?>
        <?=Html::submitButton("Сохранить", ['class' => 'btn btn-success'])?>
        <? ActiveForm::end()?>
        </div>
    </div>
</div>
<?
/*if(!$model->isNewRecord){
    ?>
    <pre>
        <?var_dump($model->childrens)?>
    </pre>
    <?
}