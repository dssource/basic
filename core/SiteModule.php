<?php

namespace dssource\basic\core;

use dssource\basic\core\ModuleInterface;
use yii\base\Exception;
use yii\web\GroupUrlRule;
use Yii;
/**
 * Class Admin Module
 * @package app\modules\admin
 */
class SiteModule extends \yii\base\Module implements ModuleInterface
{

    public $controllerNamespace = 'dssource\basic\controllers';
    public $imagePath = 'res/images'; // prefix @webroot | @web
    private $_theme;

    public $urlRules = [
            '' => 'site/default/index',
    ];

    // Разделы и страницы сайта
    public $contentUrlRules = [
        //'prefix' => 'content',
        'routePrefix' => 'site',
        'rules' => [
            // Ошибки
            'error' => 'default/error',
            // Разделы
            'sections' => 'default/section-default-view',
            '<section:[\w-\/]+>' => 'default/section-view',
            //Страницы
            '<page:[\w-]+>.html' => 'default/page-view',
            '<section:[\w-\/]+>/<page:[\w-]+>.html' => 'default/page-view',
        ],
    ];

    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules($this->urlRules);
        //$app->getUrlManager()->rules[] = new GroupUrlRule($this->urlRules);
        //$app->getUrlManager()->rules[] = new GroupUrlRule($this->adminUrlRules);
        $app->getUrlManager()->rules[] = new GroupUrlRule($this->contentUrlRules);
    }

    public static function menu()
    {
        // TODO: Implement menu() method.
        return [
            [
                'label' => '<a href="#"><span class="glyphicon glyphicon-th"></span> Контент</a>',
                'items' => [
                    [
                        'label' => 'Разделы',
                        'url' => ['/admin/sections']
                    ],
                    [
                        'label' => 'Страницы',
                        'url' => ['/admin/pages']
                    ],
                    [
                        'label' => 'Пункты меню',
                        'url' => ['/admin/menu']
                    ],
                    [
                        'label' => 'Темы',
                        'url' => ['/admin/themes']
                    ]
                ],

            ]
        ];
    }
}