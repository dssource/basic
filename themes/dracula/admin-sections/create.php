<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['url' => '/admin/sections', 'label' => 'Разделы'];
$this->params['breadcrumbs'][] = 'Создать раздел';

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
            ['template'=>"{label}\n<div class=\"input-group\"><span class=\"input-group-addon\">".Url::home(true)."</span>{input}\n</div>\n{hint}\n{error}"]);
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
      $('#page-name').liTranslit({
		elAlias: $('#page-alias')
	    });
     
});
JS;

Yii::$app->getAssetManager()->publish('@dssource/site/source/js/jquery.liTranslit.js');


$this->registerJsFile(Yii::$app->getAssetManager()->getPublishedUrl('@dssource/site/source/js/jquery.liTranslit.js'), ['depends' => 'yii\web\JqueryAsset']);
$this->registerJs($js_script);

?>