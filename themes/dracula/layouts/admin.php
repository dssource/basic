<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use dssource\basic\assets\dracula\AdminAsset;
use dssource\basic\models\Module;

AdminAsset::register($this);
$modules = new Module();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-2 no-padding">
                <?=$modules->adminVerticalMenu()?>
                <?//=var_dump($modules->toWidget())?>
            </div>
            <div class="col-xs-10">
                <?= Breadcrumbs::widget([
                    'homeLink' => ['label' => 'Управление', 'url' => '/admin'],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

                <?php
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                    echo '<div class="alert alert-' . $key . ' ">'.
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.
                        $message . '</div>';
                }
                ?>
                <?= $content ?>
            </div>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
