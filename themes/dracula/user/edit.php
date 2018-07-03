<?php

use \yii\bootstrap\Html;
use \yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;


$this->params['breadcrumbs'][] = ['url' => '/user/'.$data->username, 'label' => 'Профиль пользователя '.$data->username];
$this->params['breadcrumbs'][] = 'Редактировать';



?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Основные данные</div>
                <div class="panel-body">
                    <?$form = ActiveForm::begin(['action' => '?update=main'])?>
                    <?=$form->field($model, 'name')->textInput(['value' => $data->name])?>
                    <?=$form->field($model, 'surname')->textInput(['value' => $data->surname])?>
                    <?=$form->field($model, 'last_name')->textInput(['value' => $data->last_name])?>
                    <?=$form->field($model, 'brightDay')->widget(DatePicker::className(), [
                        'language' => 'ru',
                        'clientOptions' => [
                            'dateFormat' => 'dd.mm.yyyy',
                            'defaultDate' => $data->brightDay
                        ]
                    ])?>
                    <?=$form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(),['mask'=>'+7 999 999-99-99'])->textInput(['value' => $data->phone, 'placeholder' => '+7 999 999-99-99'])?>
                    <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> Сохранить изменения', ['class' => 'btn btn-success'])?>
                    <? ActiveForm::end()?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-upload"></span>  Фотография</div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-5 text-center">
                                <?=Html::img((($data->photo == null) ? $data->defaultImage : $data->photo),
                                    ['style' => 'width: 150px; height: 150px', 'class' => 'img-circle'])?>
                            </div>
                            <div class="col-md-7">
                                <?$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => '?update=photo'])?>
                                <?=$form->field($model, 'photo')->fileInput(['accept' => 'image/*'])?>
                                <?=Html::submitButton('<span class="glyphicon glyphicon-save"></span> Загрузить', ['class' => 'btn btn-info btn-sm'])?>
                                <? ActiveForm::end()?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-send"></span> Сменить электрорнную почту</div>
                <div class="panel-body">
                    <?$form = ActiveForm::begin(['action' => '?update=email'])?>
                    <?=$form->field($model, 'email')->textInput(['value' => $data->email])?>
                    <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> Сохранить изменения', ['class' => 'btn btn-success'])?>
                    <? ActiveForm::end()?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-lock"></span>  Сменить пароль</div>
                <div class="panel-body">
                    <?$form = ActiveForm::begin(['action' => '?update=password'])?>
                    <?=$form->field($model, 'password')->passwordInput()?>
                    <?=$form->field($model, 'new_password')->passwordInput()?>
                    <?=$form->field($model, 'repeat_new_password')->passwordInput()?>
                    <?=Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> Сохранить изменения', ['class' => 'btn btn-success'])?>
                    <? ActiveForm::end()?>
                </div>
            </div>
        </div>
    </div>
</div>