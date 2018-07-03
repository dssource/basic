<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use dssource\basic\assets\ds\BasicAsset;
use dssource\basic\widgets\Alert;
use dssource\basic\models\Menu;

BasicAsset::register($this);


//exit(var_dump(Menu::getItems()));
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<div class="wrap">
<?php
NavBar::begin([ // отрываем виджет
    'brandLabel' => 'Моя организация', // название организации
    'brandUrl' => Yii::$app->homeUrl, // ссылка на главную страницу сайта
    'options' => [
        'class' => 'navbar-inverse', // стили главной панели
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'], // стили ul
    'encodeLabels' => false,
    'items' => Menu::getItems(),
        /*
         Yii::$app->user->isGuest ? // Если пользователь гость, показыаем ссылку "Вход", если он авторизовался "Выход"
            ['label' => 'Вход', 'url' => ['/user/login']] :
            [
                'label' => '' . Yii::$app->user->identity->username . '',
                'items'=>[
                    ['label' => 'профиль', 'url' => ['/user/'.Yii::$app->user->identity->username], 'options' => ['class' => '']],
                    Yii::$app->user->can('admin') ?
                    ['label' => 'Управление', 'url' => ['/admin/'], 'options' => ['class' => '']] : null,
                    ['label' => 'Выход', 'url' => ['/user/logout'], 'options' => ['class' => '']],
                    ],
            ]

    ],*/

]);
NavBar::end(); // закрываем виджет
?>
    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
    <?php $this->beginBody() ?>
        <?= $content ?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
