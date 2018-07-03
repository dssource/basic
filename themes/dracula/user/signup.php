<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
use yii\captcha\Captcha;
$this->params['breadcrumbs'][] = 'Регистрация';

//echo 'Current strategy: '.$strategy;
?>
<div class="container-fluid">
    <?php
        if($info != NULL)
        {
            echo '<div class="row">'
                .'  <div class="col-xs-12">'
                .'<blockquote class="blockquote">'
                .'<p>'.$info.'</p>'
                .'</blockquote>'
                .'  </div>'
                .'</div>';
        }
    ?>
    <div class="row">
        <?php $form = ActiveForm::begin();
        foreach($model->attributes as $key => $value)
        {
            if(in_array($key, $model->scenarios()[$strategy]))
            {
                switch($key):
                case 'password':
                case 'password_repeat':
                    echo $form->field($model, $key)->passwordInput();
                    break;

                case 'verifyCode':
                    echo $form->field($model, $key)->widget(Captcha::className(), [
                        'captchaAction'=>'/user/captcha',
                        'template'=>"{input} \n{image} "
                    ]);
                    continue;
                break;

                default:
                    echo $form->field($model, $key);
                    break;

                endswitch;
            }

        }

        echo Html::submitButton("Сохранить", ['class' => 'btn btn-success']);
        ActiveForm::end(); ?>
    </div>
</div>