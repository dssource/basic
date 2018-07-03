<?php

use yii\widgets\Breadcrumbs;
use yii\grid\GridView;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = 'Управление пунктами меню';

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <a href="/admin/menu/create" class="btn btn-sm btn-success">Создать</a>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?//var_dump($dataProvider);?>
            <?=GridView::widget([
                // полученные данные
                'dataProvider' => $dataProvider,
                // Отображать 5 страниц
                'pager' => ['maxButtonCount' => 5],
                // колонки с данными
                'columns' => [
                    [
                        'attribute' => 'label',
                        'format' => 'raw',
                        'contentOptions' => function ($model)
                        {
                            if($model->id_parent > 0) return ['style' => 'padding: 0px;'];
                            else
                                return [];
                        },
                        'value' => function ($model)
                        {
                            $style = '';
                            if($model->id_parent > 0)
                            {
                                $style = ' style="margin:0px; height: 100%; padding: 8px; border-left: '.(8*($model->attachmentLevel-1)).'px solid #bababa"';
                            }
                            return '<p'.$style.'>'.$model->label.'</p>';
                        }
                    ],
                    'url:ntext',
                    [
                        'attribute' => 'accept',
                        'format' => 'raw',
                        'value' => function($model){
                           return $model->acceptArray()[$model->accept];
                        }
                    ],
                    'active:boolean',
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{update} {delete}',
                        'buttons' =>[
                            'update' => function ($id, $model){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '/admin/menu/update/'.$model->id);
                            },
                            'delete' => function ($id, $model){
                                return ($model->id != 1) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', '/admin/menu/delete/'.$model->id, ['data-confirm' => 'Вы уверены, что хотите удалить этот элемент?']) : '';
                            }
                        ]
                    ],
                ],
            ])
            ?>
        </div>
    </div>
</div>
