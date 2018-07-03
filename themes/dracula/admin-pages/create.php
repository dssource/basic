<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dssource\basic\widgets\Redactor;
use dssource\basic\widgets\Vkimport;

$this->params['breadcrumbs'][] = ['url' => '/admin/pages', 'label' => 'Страницы'];
if($model->isNewRecord) $this->params['breadcrumbs'][] = 'Создать страницу';
else
    $this->params['breadcrumbs'][] = 'Редактировать страницу: '.Html::encode($model->name);

$jsAliasDisabled = 'false';

if(!$model->isNewRecord)
{
    $jsAliasDisabled = 'true';
}
?>
<? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
<div class="container-fluid">
    <div class="row">
        <!-- LEFT BLOCK -->
        <div class="col-md-8">
            <?
            echo $form->field($model, 'content')->widget(Redactor::className())->label('Оформление страницы');

            ?>
            <?=Html::submitButton("Сохранить", ['class' => 'btn btn-success'])?>
            <? ActiveForm::end()?>
        </div>
        <!-- /LEFT BLOCK -->
        <!-- RIGHT BLOCK -->
        <div class="col-md-4">
            <?
            //if(!$model->isNewRecord)
            echo "\r\n".'<div>'.Html::img($model->imageUrl, ['class' => 'img-responsive', 'style' => 'max-height: 100px;']).'</div>'."\r\n";

            echo $form->field($model, 'imageFile')->fileInput(['accept' => 'image/*']);

            echo $form->field($model, 'name');
            if($model->isNewRecord)
                echo $form->field($model, 'alias');
            else {
                echo $form->field($model, 'alias',
                    ['template' => "{label}\n<div class=\"input-group\">{input}\n<span class=\"input-group-addon\"><button type=\"button\" id=\"aliasLock\"><span class=\"glyphicon glyphicon-lock\"></span></button></span>\n</div>\n{hint}\n{error}"]);
                echo '<p class="text-info"><small><strong>Осторожно!</strong> Не меняйте адрес раздела без крайней необходжимости, возможно адрес уже был проиндексирован поисковыми системами</small></p>';
            }

            echo $form->field($model, 'id_section')->dropDownList($model->sectionList());
            echo $form->field($model, 'short')->textarea();
            ?>
            <?php
                echo $form->field($model, 'keywords');
                echo $form->field($model, 'is_publish')->checkbox();
                if(!$model->isNewRecord)
                    echo $form->field($model, 'vk_import')->widget(Vkimport::className())->label('<span class="glyphicon glyphicon-bullhorn"></span> ВК');
            ?>
            <div>
                <span class="glyphicon glyphicon-eye-open"></span> Адрес: <?=($model->publish ? Html::a($model->absoluteUrl, $model->absoluteUrl) : 'Не опубликовано');?>
            </div>
        </div>
        <!-- / RIGHT BLOCK -->
    </div>
</div>

<?php

$js_script = <<<JS
$(document).ready(function(){
    console.log('It work');
        $("#page-alias").prop('readonly', $jsAliasDisabled);
        $(".glyphicon-lock").css('color', '#66b166');

        $("#aliasLock").click(function(){

            if(!$('#page-alias').prop('readonly'))
                {
                     $('#page-name').liTranslit('disable');
                      $(".glyphicon-lock").css('color', '#66b166');
                      $('#page-alias').prop('readonly', true)
                }
                else
                {
                     $('#page-name').liTranslit('enable');
                     $("#page-alias").prop('readonly', false);
                      $(".glyphicon-lock").css('color', '#ff0000');
                }
        });

      $('#page-name').liTranslit({
		elAlias: $('#page-alias')
	    });
     
});
JS;

$this->registerJs($js_script);

?>