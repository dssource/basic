<?php
use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use \yii\bootstrap\Html;

$this->params['breadcrumbs'][] = 'Модули';

echo '<div class="container-fluid">';

Modal::begin([
    'header' => '<h2>Новый модуль</h2>',
    'toggleButton' => [
        'label' => '<span class="glyphicon glyphicon-plus"></span> Новый модуль',
        'tag' => 'button',
        'class' => 'btn btn-success btn-sm',],
    'footer'=> 'Обратите внимание, что для работы ModuleManager Ваш класс модуля должен реализовывать <code>dssource\modulemanager\ModuleInterface</code>'
]);
?>
<? $form = ActiveForm::begin()?>
<?=$form->field($model, 'name');?>
<?=$form->field($model, 'class');?>
<?=$form->field($model, 'options');?>
<?=$form->field($model, 'label');?>
<?=$form->field($model, 'position')->input('number');?>
<?=$form->field($model, 'icon');?>
<?=Html::submitButton('Сохранить', ['class' => 'btn btn-success']);?>
<? ActiveForm::end()?>
<?
Modal::end();
?>
<br><br>
<?
    echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'icon',
            'format' => 'raw',
            'value' => function($model) {
                return '<span class="'.$model->icon.'"></span>';
            },
        ],
        'label',
        [
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function($model){
                $st = Html::encode($model->name);
                if($model->is_active == 0) $st .= ' <span class="bange">Не активный</span>';

                return $st;
            }
        ],
        'class',
        'options',
        'position',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        "/admin/modules/update/".$model->id);
                },
                'link' => function ($url,$model,$key) {
                    return Html::a('Действие', $url);
                },
            ],
        ],
    ]
]);
?>
</div>