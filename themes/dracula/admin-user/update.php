<?php
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use \yii\grid\GridView;
use yii\widgets\Breadcrumbs;

$this->params['breadcrumbs'][] = ['url' => \yii\helpers\Url::to("/admin/user"), 'label' => 'Пользователи'];
$this->params['breadcrumbs'][] =$model->username;

//    foreach(array_keys($model->attributes) as $v)
//        $st .= ("'".$v."',");
//
//exit ($st);
?>
<div class="container-fluid">
    <div class="row">

           <?php $form = ActiveForm::begin(['method' => 'post']);

           foreach ($model->attributes as $key => $value)
           {
               if(in_array($key, [''])) continue;
               echo '<div class="col-xs-6">'.
                   $form->field($model, $key)->textInput(['value' => $value]).
               '</div>';
           }

           ?>

        <div class="col-xs-12 text-center">
            <?=Html::submitButton("Сохранить", ['class' => 'btn btn-success'])?>
        </div>
        <? ActiveForm::end()?>
    </div>
</div>