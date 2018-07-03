<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;

$this->params['breadcrumbs'][] = ['url' => '/admin/modules', 'label' => 'Модули'];
$this->params['breadcrumbs'][] = $model->label;
?>
<div class="container-fluid">
    <div class="row text-left">
<?
$form = ActiveForm::begin(['method' => 'post']);
foreach ($model->attributes as $key=>$value)
{
    if($key == 'id') continue;
    if($key == 'is_active'){
        echo $form->field($model, $key)->checkbox();
    }
    else {
        echo $form->field($model, $key)->textInput(['value' => Html::encode($value)]);
    }

}
echo Html::submitButton("Сохранить", ['class' => 'btn btn-success']);
ActiveForm::end();
?>
        </div>
    </div>
