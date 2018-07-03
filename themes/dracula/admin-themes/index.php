<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\grid\GridView;

$this->params['breadcrumbs'][] = 'Темы';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <a href="/admin/themes/create" class="btn btn-sm btn-success">Создать</a>
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
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function ($model)
                        {
                            return ($model->active ? '<b>'.$model->name.'</b> <span class="text-success">(Активная)</span>' : $model->name);
                        }
                    ],
                    'path:ntext',
                    'description:ntext',
                    'author:ntext',
                    'version:ntext',
                    [
                        'attribute' => 'options',
                        'format' => 'raw',
                        'value' => function ($model)
                        {
                            return $model->options;
                        }
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{set} {update}',
                        'buttons' =>[
                            'set' => function ($id, $model){
                                return (!$model->active ? Html::a('<span class="glyphicon glyphicon-ok"></span>', '/admin/themes/set/'.$model->id, ['data-confirm' => 'Вы уверены, что хотите установить эту тему?']) : '');
                            },
                            'update' => function ($id, $model){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '/admin/themes/update/'.$model->id);
                            },

                        ]
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
