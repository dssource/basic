<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */
use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
use dssource\basic\assets\PageViewAsset;

PageViewAsset::register($this);

$this->params['breadcrumbs'] = $model->breadcrumbs;
$this->params['breadcrumbs'][] = Html::encode($model->name);
$this->title = Html::encode($model->name);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?=Html::img($model->imageUrl, ['class' => 'img-responsive', 'style' => 'max-height: 130px;'])?>
        </div>
        <div class="col-md-10">
            <h1><?=$model->name?></h1>
            <?=$model->short?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
           <?=$model->content?>
        </div>
    </div>
</div>

