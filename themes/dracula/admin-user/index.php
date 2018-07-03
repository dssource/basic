<?php
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use \yii\grid\GridView;
use yii\widgets\Breadcrumbs;
$this->params['breadcrumbs'][] = 'Пользователи';

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-6">
            <?=Html::a("<span class=\"glyphicon glyphicon-plus\"></span> Добавить", "/user/new", ['class' => 'btn btn-success btn-sm'])?>
            <?=Html::a("<span class=\"glyphicon glyphicon-lock\"></span> Полномочия", "/admin/rbac", ['class' => 'btn btn-danger btn-sm'])?>
        </div>
        <div class="col-xs-6 text-right">
            <div class="text-center alert alert-warning fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                Текущаяя стратегия: <strong><?=$currentStrategy?></strong>
            </div>
        </div>
    <hr>
    <?php
        if($activationCount > 0 )
    echo '<div class="row">'.
        '   <div class="col-xs-12">'.
        '   Требуют активации: <b>'.$activationCount.'</b>'.
        '   </div>'.
        '</div>';
    ?>
    <div class="row">
        <div class="col-xs-12">
            <?=GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $attributes,
            ])?>
        </div>
    </div>
</div>