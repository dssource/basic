<?php

use \yii\bootstrap\Html;
use \yii\grid\GridView;
use yii\widgets\Breadcrumbs;

$this->params['breadcrumbs'][] = 'Профиль: '.$model->username;



?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 text-center">
            <?=Html::img((($model->photo == null) ? $model->defaultImage : $model->photo),
                ['style' => 'width: 150px; height: 150px; margin-bottom: 50px', 'class' => 'img-circle'])?>
        </div>
        <div class="col-md-6">
            <h1 class="text-left"><?=$model->username?> <span class="badge"<?=($model->isOnline() ? ' style="background-color: green"' : '')?>>
                    <?=($model->isOnline() ? 'Онлайн' : 'Нет на сайте')?>
                </span> </h1>
            <p><?=$model->surname.' '.$model->name.' '.$model->last_name?></p>
        </div>
        <?php
        if(Yii::$app->user->identity->id == $model->id)
        {
            ?><div class="row">
            <div class="col-md-3 text-right">
                <a href="/user/edit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Редактировать</a>
                <br>
                <br>
            </div>
        </div>
            <?
        }
        ?>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?=\yii\widgets\DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'brightDay',
                        'format' => 'raw',
                        'value' => function($model)
                        {
                            return ($model->brightDay == '' ? '(не задано)' : date("d.m.Yг. в H:i", $model->brightDay).' ('.$model->zodiac.')');
                        }
                    ],
                    'phone',
                    'email',
                    [
                        'attribute' => 'last_active',
                        'format' => 'raw',
                        'value' => function($model)
                        {
                            return date("d.m.Yг. в H:i", $model->last_active);
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function($model)
                        {
                            return $model->statusNames[$model->status];
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'raw',
                        'value' => function($model)
                        {
                            return date("d.m.Yг.", $model->created_at);
                        }
                    ],
                ]
            ])?>
        </div>
    </div>
</div>