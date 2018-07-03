<?php
namespace dssource\basic\core;

use Yii;
use yii\web\GroupUrlRule;

/**
 * Class Admin Module
 * @package app\modules\admin
 */
class AdminModule extends \yii\base\Module implements ModuleInterface
{
    /**
     * @var string the namespace that controller classes are in.
     */
    public $controllerNamespace = 'dssource\basic\controllers';
    public $layout = 'admin';
    /**
     * Initializes the module.
     */
    public function init()
    {
        parent::init();
    }

    public $urlRules = [
        'prefix' => 'admin',
        'rules' => [
            '' => 'admin/index',
            'assets-clear' => 'admin/assets-clear',
            'modules' => 'admin-modules/index',
            'modules/update/<id:[\d+]>' => 'admin-modules/update',
            // Разделы
            'sections' => 'admin-sections/index',
            'sections/create' => 'admin-sections/create',
            'sections/<action:(update|delete)>/<id:[\d]+>' => 'admin-sections/<action>',
            // Страницы
            'pages' => 'admin-pages/index',
            'pages/create' => 'admin-pages/create',
            'pages/<action:(update|delete)>/<id:[\d]+>' => 'admin-pages/<action>',
            // Темы
            'themes' => 'admin-themes/index',
            'themes/create' => 'admin-themes/create',
            'themes/<action:(update|delete|set)>/<id:[\d]+>' => 'admin-themes/<action>',
            // Пункты меню
            'menu' => 'admin-menu/index',
            'menu/create' => 'admin-menu/create',
            'menu/<action:(update|delete)>/<id:[\d]+>' => 'admin-menu/<action>',
            //Пользователи
            'user' => 'admin-user/index',
            'user/<action:(view|update|delete|activate|block|role)>/<id:[\d+]>' => 'admin-user/<action>',
            'rbac' => 'admin-rbac/index',
            'rbac/create/<item:(role|permission)>' => 'admin-rbac/create',
            'rbac/deletePermission/<name:[\w-]+>' => 'admin-rbac/delete-permission',
            'rbac/deleteRole/<name:[\w-]+>' => 'admin-rbac/delete-role',
            'rbac/update-role/<name:[\w-]+>' => 'admin-rbac/update-role',
            'rbac/update-permission/<name:[\w-]+>' => 'admin-rbac/update-permission',
            'rbac/inheritance/<name:[\w-]+>' => 'admin-rbac/inheritance',
            'rbac/un-inheritance/<rbacItemParent:[\w-]+>/<rbacItemChild:[\w-]+>' => 'admin-rbac/un-inheritance',
        ],
    ];

    public function bootstrap($app)
    {
        $app->getUrlManager()->rules[] = new GroupUrlRule($this->urlRules);
    }

    public function behaviors(){
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['developer', 'admin'],
                    ],
                ],
            ],
        ];
    }

    public static function menu()
    {
        // TODO: Implement menu() method.
        return false;
    }
}