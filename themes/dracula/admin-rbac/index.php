<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$dataProvider = new ArrayDataProvider([
    'allModels' => Yii::$app->authManager->getRoles(),
    'sort' => [
        'attributes' => ['name', 'description'],
    ],
    'pagination' => [
        'pageSize' => 10,
    ],
]);

$this->params['breadcrumbs'][] = ['url' => '/admin/user', 'label' => 'Пользователи'];
$this->params['breadcrumbs'][] = 'Полномочия пользователей';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <?php
            \yii\bootstrap\Modal::begin([
                'header' => '<h2>Добавить роль</h2>',
                'toggleButton' => ['label' => '<span class="glyphicon glyphicon-plus"></span>  Добавить роль', 'class' => 'btn btn-success']
                ]);
            ?>
            <?php $form = ActiveForm::begin(['action' => '/admin/rbac/create/role']);

            echo $form->field($rbacRoleForm, 'name');
            echo $form->field($rbacRoleForm, 'description')->textarea();
            echo $form->field($rbacRoleForm, 'permission')->listBox($permissions, ['multiple' => true]);
            ?>
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            <?\yii\bootstrap\Modal::end();?>

            <?php
            \yii\bootstrap\Modal::begin([
                'header' => '<h2>Добавить полномочие</h2>',
                'toggleButton' => ['label' => '<span class="glyphicon glyphicon-plus"></span> Добавить полномочие', 'class' => 'btn btn-info']
            ]);
            ?>
            <?php $form2 = ActiveForm::begin(['action' => '/admin/rbac/create/permission']);

            echo $form2->field($rbacPermissionForm, 'name');
            echo $form2->field($rbacPermissionForm, 'description')->textarea();
            ?>
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
            <?\yii\bootstrap\Modal::end();?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-5">
            <?=GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'class'     => DataColumn::className(),
                        'attribute' => 'name',
                        'label'     => 'Роль'
                    ],
                    [
                        'class'     => DataColumn::className(),
                        'attribute' => 'description',
                        'label'     => 'Описание',
                        'content'   =>  function($model){
                            $in = Yii::$app->authManager->getChildRoles($model->name);
                            unset($in[$model->name]);
                            if(count($in)>0)
                            {
                                $st = '<ul class="list-group">';
                                foreach ($in as $item)
                                {
                                    $st .= '<li class="list-group-item">'.
                                        '<p><span class="glyphicon glyphicon-import"></span> '.
                                        $item->description.' '.Html::a('<span class="glyphicon glyphicon-remove pull-right"></span>',
                                            '/admin/rbac/un-inheritance/'.$model->name.'/'.$item->name,
                                            ['style' => 'color: red', 'class' => 'pull-right']).
                                        '</p></li>';
                                }
                                $st .= '</ul>';
                            }
                            return $model->description.'<br>'.$st;
                        },
                    ],
                    [
                        'class'     => DataColumn::className(),
                        'label'     => 'Разрешенные доступы',
                        'format'    => ['html'],
                        'value'     => function($data)
                        {
                            $p = Yii::$app->authManager->getPermissionsByRole($data->name);
                            if(count($p) >0)
                            {
                                //exit(var_dump(Yii::$app->authManager->getPermissionsByRole($data->name)));
                                $st = '<ul class="list-group">';
                                foreach ($p as $item)
                                {
                                    $st .= '<li class="list-group-item">'.
                                        $item->description;
                                            $st .= Html::a('<span class="glyphicon glyphicon-remove"></span>',
                                            '/admin/rbac/un-inheritance/'.$data->name.'/'.$item->name,
                                            ['style' => 'color: red', 'class' => 'pull-right']);
                                    $st .= '</li>';
                                }
                                $st .= '</ul>';
                            }
                            return $st;
                        }
                    ],
                    ['class' => 'yii\grid\ActionColumn',

                        'template' => '{update} {delete} {inheritance}',
                        'buttons' =>
                            [
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '/admin/rbac/update-role/'.$model->name, [
                                        'title' => 'Update',
                                    ]); },
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', '/admin/rbac/deleteRole/'.$model->name, [
                                        'title' => 'Delete',
                                        'data-confirm' =>'Are you sure you want to delete this item?'
                                    ]);
                                },
                                'inheritance' => function ($url, $model)
                                {
                                    return Html::a('<span class="glyphicon glyphicon-retweet"></span>', '/admin/rbac/inheritance/'.$model->name, [
                                        'title' => 'Наследовать',
                                    ]);
                                }
                            ]
                    ],
                ]
            ]);
            ?>
        </div>
        <div class="col-xs-1"></div>
        <div class="col-md-5">
            <?php
            $dataProvider2 = new ArrayDataProvider([
                'allModels' => Yii::$app->authManager->getPermissions(),
                'sort' => [
                    'attributes' => ['name', 'description'],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            ?>

            <?=GridView::widget([
                'dataProvider' => $dataProvider2,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'class'     => DataColumn::className(),
                        'attribute' => 'name',
                        'label'     => 'Правило'
                    ],
                    [
                        'class'     => DataColumn::className(),
                        'attribute' => 'description',
                        'label'     => 'Описание'
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' =>
                            [
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                        '/admin/rbac/update-permission/'.$model->name);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                        '/admin/rbac/deletePermission/'.$model->name);
                                }
                            ]
                    ],
                ]
            ]);
            ?>
        </div>
    </div>
</div>