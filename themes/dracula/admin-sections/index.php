<?php

use yii\widgets\Breadcrumbs;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Управление разделами';
$this->params['breadcrumbs'][] = 'Разделы';

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <a href="/admin/sections/create" class="btn btn-sm btn-success">Создать</a>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?=GridView::widget([
                // полученные данные
                'dataProvider' => $dataProvider,
                // Отображать 5 страниц
                'pager' => ['maxButtonCount' => 5],
                // колонки с данными
                'columns' => [
                    [
                        'attribute' => 'image',
                        'label' => 'Изображение',
                        'format' => 'raw',
                        'value' => function($model) {
                            return Html::img($model->imageUrl, ['style' => 'width: 50px; height: 50px;']);
                        }
                    ],
                    [
                        'attribute' => 'name',
                        'label' => 'Имя',
                        'format' => 'raw',
                        'value' => function($model)
                        {

                            $st = '<p>'.$model->fullPath.'</p>';
                            if($model->id == 1)
                                $st .= '<p><span class="label label-default">Системный корневой каталог</span> </p>';
                                else
                                    $st .= '<p>'.Html::a($model->url, $model->url).'</p>';

                            return $st;
                        }
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{update} {delete}',
                        'buttons' =>[
                            'update' => function ($id, $model){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '/admin/sections/update/'.$model->id);
                            },
                            'delete' => function ($id, $model){
                                return ($model->id != 1) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', '/admin/sections/delete/'.$model->id, ['data-confirm' => 'Вы уверены, что хотите удалить этот элемент?']) : '';
                            }
                        ]
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
