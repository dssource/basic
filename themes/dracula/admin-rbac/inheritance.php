<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$this->params['breadcrumbs'][] = ['url' => '/admin/user', 'label' => 'Пользователи'];
$this->params['breadcrumbs'][] = ['url' => '/admin/rbac', 'label' => 'Полномочия пользователей'];
$this->params['breadcrumbs'][] = 'Наследование роли: '.$role->name;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <? $form = ActiveForm::begin()?>
            <?=Html::label("Добавить все полномочия роли:")?>
            <?=Html::dropDownList('inheritance',[], $roles, ['class' => 'form-control'])?>
            <br>
        </div>
        <div class="col-xs-12">
            <?=Html::submitButton("Наследовать", ['class' => 'btn btn-success'])?>
            <? ActiveForm::end()?>
        </div>
    </div>
</div>
