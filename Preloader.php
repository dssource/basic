<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic;

use Yii;
use yii\base\Theme;
use yii\base\BootstrapInterface;
use dssource\basic\core\Themes;
use dssource\basic\models\Module;

class Preloader implements BootstrapInterface
{
    public function bootstrap($app)
    {
        // Кофигурация приложения.
        // Алиас
        Yii::setAlias('@dssource/basic', __DIR__);
        //Ошибки
        // Обработчик ошибок
        Yii::$app->errorHandler->errorAction = 'site/default/error';

        // Темы
        $currentTheme = '@app/themes/'.Themes::activeTheme();
        $defaultTheme = '@app/themes/dracula';


        // Динамическое подключение модулей
        // получаем список модулей
        $modules = new Module;
        $modules = $modules->findActive();

        // Формируем pathMap
        $pathMap = [];

        foreach ($modules as $module)
        {
            if(!class_exists($module->class)) continue;

            $options = [];
            $options['class'] = $module->class;

            if($module->options!=''){
                foreach(explode(';', $module->options) as $property){
                    list ($property_key, $property_value) = explode ("=", $property);
                    $options[$property_key] = $property_value;
                }
            }

            Yii::$app->setModule($module->name, $options);
            Yii::$app->getModule($module->name)->bootstrap(Yii::$app);

            $pathMap[Yii::$app->getModule($module->name)->viewPath] = [$currentTheme, $defaultTheme];
            //var_dump($module);
        }


        Yii::$app->view->theme = new Theme(
            [
                'basePath' => $currentTheme,
                'baseUrl' => '@web',
                'pathMap' => array_merge([
                    '@app/views' => [$currentTheme, $defaultTheme],
                    '@dssource/basic/core/views' => [$currentTheme, $defaultTheme],
                ],
                $pathMap)
            ]
        );

    }
}