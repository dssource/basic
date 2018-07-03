<?php
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
$this->params['breadcrumbs'][] = 'Вход';
?>
<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin();
        echo $form->field($model, 'username');
        echo $form->field($model, 'password')->passwordInput();
        echo $form->field($model, 'rememberMe')->checkbox();
        echo Html::submitButton("Войти", ['class' => 'btn btn-success']);
        ActiveForm::end(); ?>
    </div>
    <br>
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <a href="/user/register"><span class="glyphicon glyphicon-plus-sign"></span> Регистрация</a><br>
            <a href="/user/recovery"><span class="glyphicon glyphicon-question-sign"></span> Я забыл пароль</a>
        </div>
    </div>
</div>