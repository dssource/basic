<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['url' => '/admin/sections', 'label' => 'Разделы'];
$this->params['breadcrumbs'][] = 'Изменить раздел';

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
        <? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
        <?php
        echo $form->field($model, 'imageFile')->fileInput(['accept' => 'image/*']);
        echo $form->field($model, 'isParent')->checkbox();
        echo $form->field($model, 'id_parent')->dropDownList(ArrayHelper::map($model->sections(), 'id', 'name'));
        echo $form->field($model, 'name');
        echo $form->field($model, 'alias',
            ['template'=>"{label}\n<div class=\"input-group\"><span class=\"input-group-addon\">".Url::home(true)."</span>{input}\n<span class=\"input-group-addon\"><button type=\"button\" id=\"aliasLock\"><span class=\"glyphicon glyphicon-lock\"></span></button></span>\n</div>\n{hint}\n{error}"]);
        echo '<p class="text-info"><small><strong>Осторожно!</strong> Не меняйте адрес раздела без крайней необходжимости, возможно адрес уже был проиндексирован поисковыми системами</small></p>';
        echo $form->field($model, 'description')->textarea();
        echo $form->field($model, 'show_in_catalog')->checkbox();
        echo $form->field($model, 'default_item_id');

        ?>
        <?=Html::submitButton("Сохранить", ['class' => 'btn btn-success'])?>
        <? ActiveForm::end()?>
        </div>
    </div>
</div>
<pre>
    <?  var_dump(ArrayHelper::map($model->sections(), 'id', 'name')) ?>
</pre>

<?php

$js_script = <<<JS
$(document).ready(function(){
    console.log('It work');
    
    
    $("#section-alias").prop('readonly', true);
    $(".glyphicon-lock").css('color', '#66b166');
    
    if($("#section-isparent").is(":checked"))
        {
            $(".field-section-id_parent").hide();
        }
        
       $("#section-isparent").change(function(){        
                    if($("#section-isparent").is(":checked"))
                    {
                         $(".field-section-id_parent").hide(1000);
                    }
                    else
                    {
                         $(".field-section-id_parent").show(1000);
                    } 
       });
       
        $('#section-name').liTranslit({
		        elAlias: $('#section-alias')
	            });
	             $('#section-name').liTranslit('disable');
        
        $("#aliasLock").click(function(){
      
            if(!$('#section-alias').prop('readonly'))
                {
                     $('#section-name').liTranslit('disable');
                      $(".glyphicon-lock").css('color', '#66b166');
                      $('#section-alias').prop('readonly', true)
                }
                else 
                {
                     $('#section-name').liTranslit('enable');
                     $("#section-alias").prop('readonly', false);
                      $(".glyphicon-lock").css('color', '#ff0000');
                }      
        });
});
JS;

Yii::$app->getAssetManager()->publish('@dssource/site/source/js/jquery.liTranslit.js');


$this->registerJsFile(Yii::$app->getAssetManager()->getPublishedUrl('@dssource/site/source/js/jquery.liTranslit.js'), ['depends' => 'yii\web\JqueryAsset']);
$this->registerJs($js_script);

?>