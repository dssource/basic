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
        Yii::$app->errorHandler->errorAction = '/error';
        // Темы
        $currentTheme = '@dssource/basic/themes/'.Themes::activeTheme();
        $defaultTheme = '@dssource/basic/themes/dracula';
        Yii::$app->view->theme = new Theme(
            [
                'basePath' => $currentTheme,
                'baseUrl' => '@web',
                'pathMap' => [
                    '@app/views' => [$currentTheme, $defaultTheme],
                    '@dssource/basic/core/views' => [$currentTheme, $defaultTheme],
                ],
            ]
        );

        // Динамическое подключение модулей
        // получаем список модулей
        $modules = new Module;
        $modules = $modules->findActive();

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
            //var_dump($module);
        }
        /*

        // Регистрируем модуль сайта
        Yii::$app->setModule('site', [
            'class' => 'dssource\basic\core\SiteModule',
        ]);
        Yii::$app->getModule('site')->bootstrap(Yii::$app);

        // Регистрируем модуль пользователей
        Yii::$app->setModule('user', [
            'class' => 'dssource\basic\core\UserModule',
        ]);
        Yii::$app->getModule('user')->bootstrap(Yii::$app);

        // // Регистрируем модуль Админа
        Yii::$app->setModule('admin', [
            'class' => 'dssource\basic\core\AdminModule',
        ]);
        Yii::$app->getModule('admin')->bootstrap(Yii::$app);

        //Exit("\nEnd of config");
        */
    }
}