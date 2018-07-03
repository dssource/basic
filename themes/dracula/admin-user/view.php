<?php
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use \yii\grid\GridView;
use yii\widgets\Breadcrumbs;

$this->params['breadcrumbs'][] = ['url' => \yii\helpers\Url::to("/admin/user"), 'label' => 'Пользователи'];
$this->params['breadcrumbs'][] =$model->username;



?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <?=\yii\widgets\DetailView::widget([
                'model' => $model,
                'attributes' => array_keys($model->attributes)
            ])?>
        </div>
    </div>
</div>