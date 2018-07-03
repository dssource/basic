<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */
//use Yii;
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;

$this->params['breadcrumbs'][] = ['url' => '/admin/user', 'label' => 'Пользователи'];
$this->params['breadcrumbs'][] = ['url' => '/admin/rbac', 'label' => 'Полномочия пользователей'];
$this->params['breadcrumbs'][] = 'Изменение роли: '.$role->name.'';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <? $form = ActiveForm::begin(); ?>
            <?=$form->field($roleForm, 'name')->textInput(['value' => $role->name]); ?>
            <?=$form->field($roleForm, 'description')->textarea(['value' => $role->description]); ?>
            <?=$form->field($roleForm, 'permission')->listBox($permissions, ['multiple'=>'multiple']) ?>
           <?=Html::submitButton("Сохранить", ['class' => 'btn btn-success'])?>
            <? ActiveForm::end(); ?>

        </div>
    </div>
</div>
