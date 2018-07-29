<?php

namespace dssource\basic\core;

use yii\base\Exception;
use yii\web\GroupUrlRule;
use Yii;
/**
 * Class Admin Module
 * @package app\modules\admin
 */
class UserModule extends \yii\base\Module implements ModuleInterface
{
    public $controllerNamespace = 'dssource\basic\controllers';

    private $strategy;
    private $_mail = null;

    public $avatarPath = '/avatars/';

    public $htmlLayout = '@dssource/basic/mails/layouts/html';
    public $textLayout = '@dssource/basic/mails/layouts/text';
    public $mailViewPath = '@dssource/basic/mails/views';

    const STRATEGY_FAST = 'SCENARIO_FAST';
    const STRATEGY_EMAIL = 'SCENARIO_EMAIL';
    const STRATEGY_MANAGE = 'SCENARIO_MANAGE';

    /*
     * Стратегия работы модуля:
     * 1 - Мнгновенная регистрация
     * 2 - Пароль приходит на Email
     * 3 - Всех пользователей активирует менеджер
     *
     * */
    public function getStrategy()
    {
        if($this->strategy == '')
            $this->strategy = self::STRATEGY_EMAIL;
        return $this->strategy;
    }

    public function getStrategyName()
    {
        $names = [
            'SCENARIO_FAST' => 'Быстрая регистрация',
            'SCENARIO_EMAIL' => 'Подтверждение по Email',
            'SCENARIO_MANAGE' => 'Активирует менеджер',
        ];

        return $names[$this->getStrategy()];
    }

    public function setStrategy($strategyName){
        if(substr($strategyName,0,9)!= 'SCENARIO_')
            $strategyName = 'SCENARIO_'.$strategyName;

        if(!in_array(strtoupper($strategyName), [
            self::STRATEGY_FAST,
            self::STRATEGY_EMAIL,
            self::STRATEGY_MANAGE
        ])) throw new Exception("Указанная стратегия не существует");

        $this->strategy = strtoupper($strategyName);
    }
    /**
     * @var string the namespace that controller classes are in.
     */

    /**
     * Initializes the module.
     */
    public function init()
    {
        parent::init();
    }

    public function beforeAction($action)
    {
        if(!Yii::$app->user->isGuest)
        {
            Yii::$app->user->identity->setActive();
        }

        return  parent::beforeAction($action); // TODO: Change the autogenerated stub
    }


    public $urlRules = [
        'prefix' => 'user',
        'routePrefix' => 'user',
        'rules' => [
            '' => 'user/index',
            'pm' => 'message/index',
            'pm/<action>' => 'message/<action>',
            '<action:(login|register|captcha|logout|edit)>' => 'user/<action>',
            'confirm/<username:[\w\-]+>/<actionKey:[\w\-]+>' => 'user/confirm',
            '<username:[\w-]+>' => 'user/profile',
        ],
    ];

   /* public $adminUrlRules = [
        'prefix' => 'admin',
        'routePrefix' => 'user',
        'rules' => [
            'user' => 'admin/index',
            'user/<action:(view|update|delete|activate|block|role)>/<id:[\d+]>' => 'admin/<action>',
            'rbac' => 'rbac/index',
            'rbac/create/<item:(role|permission)>' => 'rbac/create',
            'rbac/deletePermission/<name:[\w-]+>' => 'rbac/delete-permission',
            'rbac/deleteRole/<name:[\w-]+>' => 'rbac/delete-role',
            'rbac/update-role/<name:[\w-]+>' => 'rbac/update-role',
            'rbac/update-permission/<name:[\w-]+>' => 'rbac/update-permission',
            'rbac/inheritance/<name:[\w-]+>' => 'rbac/inheritance',
            'rbac/un-inheritance/<rbacItemParent:[\w-]+>/<rbacItemChild:[\w-]+>' => 'rbac/un-inheritance',
        ],
    ];
    */

    public function bootstrap($app)
    {
        $app->getUrlManager()->rules[] = new GroupUrlRule($this->urlRules);
       // $app->getUrlManager()->rules[] = new GroupUrlRule($this->adminUrlRules);
    }

    /*public function behaviors(){
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'controllers' => ['user/default'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['developer', 'admin'],
                    ],
                ],
            ],
        ];
    }*/

    // Send Mail
    public function getMail()
    {
        if ($this->_mail === null) {
            $this->_mail = Yii::$app->getMailer();
            $this->_mail->htmlLayout = $this->htmlLayout;
            $this->_mail->textLayout = $this->textLayout;
            $this->_mail->viewPath =   $this->mailViewPath;
        }

        return $this->_mail;
    }

    public static function menu()
    {
        // TODO: Implement menu() method.
        return [
            [
                'label' => '<a><span class="glyphicon glyphicon-user"></span> Пользователи</a>',
                'items' => [
                    [
                        'label' => 'Cписок',
                        'url' => ['/admin/user'],
                    ],
                    [
                        'label' => 'Полномочия',
                        'url' => ['/admin/rbac'],
                    ],
                ]
            ]
        ];
    }


    public function getStrategyAlert()
    {
        $names = [
            'SCENARIO_FAST' => NULL,
            'SCENARIO_EMAIL' => '<b>Внимание!</b> Указывайте корректный E-Майл, т.к. на него придет код для подтверждение Вашей учетной записи',
            'SCENARIO_MANAGE' => '<b>Внимание!</b> Вы получите доступ к сайту, только после проверки Ваших данных администрацией сайта. О статусе Вашей учетной записи Вы будите уведомленны по E-Mail',
        ];
        return $names[$this->getStrategy()];
    }

}