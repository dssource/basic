<?php

namespace dssource\basic\controllers;

use Yii;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use dssource\basic\models\User;
use dssource\basic\UserModule;

class AdminUserController extends Controller
{
    private $_strategy;

    public function getStrategy()
    {
        if($this->_strategy != null) return $this->_strategy;
        else
        {
            $this->_strategy = Yii::$app->getModule('user')->strategy;
            return $this->_strategy;
        }
    }

    public function actionIndex()
    {
        $model = new User;

        $dataProvider = new ActiveDataProvider([
            'query' => User::find()]);

        $activationCount = User::find()->where(['status' => User::USER_NEED_VERIFY])->count();

        foreach ($model->attributes as $key => $value)
        {
            if(in_array($key, [
                'id',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'created_at',
                'updated_at',
            ])) continue;
           switch($key):
               case 'status':
                   $attributes[] = [
                       'attribute' => 'status',
                       'label' => 'Статус',
                       'format' => 'raw',
                       'content' => function($model)
                       {
                           return '<small>Аккаунт: '.$model->getStatusName($model->status).'</small><br>'.
                           '';
                       }
                   ];
                   break;

               default:
                $attributes[] = $key.':ntext';
                break;

           endswitch;
        }

        $attributes[] = [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {activate} {role} {delete}',
            'buttons' => [
                'view' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>',
                        "/admin/user/view/".$model->id);
                },
                'update' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        "/admin/user/update/".$model->id);
                },
                'delete' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        "/admin/user/delete/".$model->id,
                        ['onClick' => 'if(confirm(\'Действительно удалить '.$model->username.'?\')) return true; else return false;']);
                },
                'activate' => function ($url,$model,$key) {
                    //if($this->getStrategy() != UserModule::STRATEGY_MANAGE) return '';
                        if($model->status == User::USER_NEED_VERIFY)
                            return Html::a(
                                '<span class="glyphicon glyphicon-star" style="color: #00aa00"></span>',
                                "/admin/user/activate/".$model->id, ['title' => 'Активировать']);
                },
                'role' => function($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-tower"></span>',
                        "/admin/user/role/".$model->id, ['title' => 'Права доступа']);
                }
            ],
        ];

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'attributes' => $attributes,
            'activationCount' => $activationCount,
            'currentStrategy' => Yii::$app->getModule('user')->getStrategyName()
        ]);
    }

    public function actionView($id){

        $model = User::findOne($id);
        if($model == null)
            throw new ForbiddenHttpException("Пользователь с таким ID не найден");

        return $this->render('view', ['model' => $model]);
    }
    public function actionRole($id){
        $model = User::findOne($id);

        if($model == null)
            throw new ForbiddenHttpException("Пользователь с таким ID не найден");
        //$model->scenario = User::SCENARIO_ADMIN;

        if(Yii::$app->request->post('userRole')!='')
        {
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole(Yii::$app->request->post('userRole')), $id);
            Yii::$app->getSession()->setFlash('success', 'Роль назначена');
        }
        
        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
        $permissions = ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'description');

        $selectedRoles = ArrayHelper::map(Yii::$app->authManager->getRolesByUser($id), 'name', 'description');
        $selectedPermissions = ArrayHelper::map(Yii::$app->authManager->getPermissionsByUser($id), 'name', 'description');

        if(count($selectedRoles) > 0)
            foreach($roles as $key => $role)
            {
                if(in_array($role, $selectedRoles))
                    unset($roles[$key]);
            }

        //if($model->load(Yii::$app->request->post()) AND $model->validate())
        //{
        //    $model->save();
        //    Yii::$app->getSession()->setFlash("success", "Данные обновлены");
        //}
        return $this->render('role', [
            'model' => $model,
            'roles' => $roles,
            'permissions' => $permissions,
            'selectedRoles' => $selectedRoles,
            'selectedPermissions' => $selectedPermissions,
            'post' => Yii::$app->request->post(),
        ]);
    }

    public function actionUpdate($id){
        $model = User::findOne($id);

        if($model == null)
            throw new ForbiddenHttpException("Пользователь с таким ID не найден");
        $model->scenario = User::SCENARIO_ADMIN;

        if($model->load(Yii::$app->request->post()) AND $model->validate())
        {
            $model->save();
            Yii::$app->getSession()->setFlash("success", "Данные обновлены");
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id){
        $model = User::findOne($id);

        if($model == null)
            throw new ForbiddenHttpException("Пользователь с таким ID не найден");
        $model->delete();

        Yii::$app->getSession()->setFlash("info", "Пользователь удален");

        return $this->render('update', ['model' => $model]);
    }

    public function actionActivate($id){
        $model = User::findOne($id);

        if($model == null)
            throw new ForbiddenHttpException("Пользователь с таким ID не найден");
        $model->status = User::USER_ACTIVE;
        $model->save();

        Yii::$app->getSession()->setFlash("info", "Пользователь активирован");

        return $this->actionIndex();
    }

    public function actionCreate()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_ADMIN;
        if($model->load(Yii::$app->request->post()) AND $model->validate())
        {
            $model->save();
            Yii::$app->getSession()->setFlash("success", "Пользователь добавлен");
        }
        return $this->render('create', ['model' => $model]);
    }
}