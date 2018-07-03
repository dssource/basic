<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\widgets;
use Yii;
use yii\base\ErrorException;
use yii\widgets\InputWidget;
use yii\helpers\Html;

class Vkimport extends InputWidget
{
    const VK_POST_ADDRESS = 'https://vk.com/post';

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $view = Yii::$app->getView();
        //$this->registerAssets();
        //$view->registerJs($this->getJs());
        //$this->options['class'] = 'form-control';
    }

    public function run()
    {
        if ($this->hasModel()) {
            //return var_dump($this->model);
            //return Html::activeTextarea($this->model, $this->attribute, $this->options);
            return $this->vkHandler();
        }
        return new ErrorException("Виджет может быть вызван только для модели");
    }

    public function getVkGroupId()
    {
        return 0;
    }

    public function vkHandler()
    {
        $value = $this->model->attributes[$this->attribute];
        if($value == '') return Html::a('Опубликовать', '#', []);
        else
            return 'Опубликовано в ВК ('.Html::a('Посмотреть', self::VK_POST_ADDRESS.$this->getVkGroupId().'_'.$value).')';
    }

    public function registerAssets()
    {
        $view = $this->getView();
        RedactorAsset::register($view);
    }

    public function getJS()
    {
        $elementId = $this->options['id'];
        $js = <<<JS
        $("document").ready(function(){
        console.log("Widget Redactor active!");
        CKEDITOR.replace("$elementId");
        });
JS;

        return $js;
    }
}