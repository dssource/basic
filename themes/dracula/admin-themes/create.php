<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => 'Темы',
    'url' => '/admin/themes'
];
if($model->isNewRecord)
    $this->params['breadcrumbs'][] = 'Добавить тему';
    else
        $this->params['breadcrumbs'][] = 'Изменить тему';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <blockquote class="blockqoute">
                <small>См. документацию по <a href="https://github.com/dssource/ds-cms-doc">темам</a></small>
            </blockquote>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
           <? $form = ActiveForm::begin();

           foreach($model->attributes as $filedKey => $fieldValue)
           {
               switch($filedKey):
                   case 'id':
                   case 'active':
                       continue;
                       break;

                  // case 'active':
                  //     echo $form->field($model, $filedKey)->checkbox();
                  //     break;

                   default:
                       echo $form->field($model, $filedKey);
                       break;

               endswitch;
           }
           ?>
           <div>
               <?=Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
           </div>
           <?
           ActiveForm::end();
           ?>
        </div>
    </div>
</div>
