<?php

namespace dssource\basic\controllers;

use dssource\basic\models\forms\Edit;
use dssource\basic\models\User;
use Yii;
use yii\bootstrap\Html;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use dssource\basic\models\forms\SignUp;
use dssource\basic\models\forms\SignIn;
use dssource\basic\core\UserModule;
use yii\web\UploadedFile;

class UserController extends Controller
{
    public function getModule()
    {
        return Yii::$app->getModule('user');
    }

    //public $layout = '@dsproject/admin/views/layout/main.php';
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                //'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'transparent' => true,
                //'foreColor' => '0x55FF00',
                'offset' => 2,
                'minLength' => 4,
                'maxLength' => 4,
                'padding' => 0
            ],
        ];
    }

    public function actionIndex()
    {
       return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignIn();
        if($model->load(Yii::$app->request->post()) AND $model->login())
        {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('signIn', ['model' => $model]);
    }

    public function actionRegister()
    {
        $scenario = Yii::$app->getModule('user')->strategy;

        $model = new SignUp(['scenario' => $scenario]);

        if($model->load(Yii::$app->request->post()) AND $model->validate())
        {
            // Быстрая регистрация
            if($scenario == UserModule::STRATEGY_FAST)
            {
                $model->fastCreate($model->username, $model->email, $model->password);
                return $this->goBack();
            }

            // Подтверждение по EMAIL
            if($scenario == UserModule::STRATEGY_EMAIL)
            {
                $model->emailCreate($model->username, $model->email);
                return $this->render('emailSend', ['model' => $model]);
            }

            // Подтверждение менеджером
            if($scenario == UserModule::STRATEGY_MANAGE)
            {
                $model->manageCreate($model->username, $model->email, $model->password);
                return $this->goBack();
            }
        }
        else
        {
            return $this->render('signUp', ['model' => $model, 'strategy' => $scenario, 'info' => Yii::$app->getModule('user')->strategyAlert]);
        }
    }

    public function actionConfirm($username, $actionKey)
    {
        if(Yii::$app->getModule('user')->strategy != UserModule::STRATEGY_EMAIL)
            throw new HttpException(400, "Режим активации аккаунтов отключен администратором");

        $model = User::getUserByUsername($username);
        if($model == null)
            throw new HttpException(400, "Пользователь ".Html::encode($username)." не найден");

        if($model->actionKey != $actionKey)
            throw new HttpException(400, "Ключ активации не действительный");

        $model->status = User::USER_ACTIVE;
        $model->save();

        Yii::$app->user->login($model, 3600*24*30);

        Yii::$app->getSession()->addFlash('success', 'Учетная запись активирована!');

        return $this->redirect('/');
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionProfile($username)
    {
        $model = User::findOne(['username' => $username]);

        if($model == null)
            throw new NotFoundHttpException("Страница не найдена");

        return $this->render("profile", ['model' => $model]);
    }

    public function actionEdit()
    {
        if(Yii::$app->user->isGuest)
            throw new ForbiddenHttpException("Функционал доступен только для аторизированных пользователей");

        $data = User::findOne(Yii::$app->user->identity->id);
        $model = new Edit();

        switch(Yii::$app->request->get('update')):
            case Edit::SCENARIO_PHOTO:
                $model->scenario = Edit::SCENARIO_PHOTO;
                break;
            case Edit::SCENARIO_PASSWORD:
                $model->scenario = Edit::SCENARIO_PASSWORD;
                break;
            case Edit::SCENARIO_CHANGE_EMAIL:
                $model->scenario = Edit::SCENARIO_CHANGE_EMAIL;
            case Edit::SCENARIO_MAIN:
                $model->scenario = Edit::SCENARIO_MAIN;
                break;
        endswitch;

        if ($model->load(Yii::$app->request->post()))
        {

            if($model->scenario == Edit::SCENARIO_PHOTO){
                $model->photo = UploadedFile::getInstance($model, 'photo');

            }

            if ($model->validate())
            {
                //die('Model is valid!'.Yii::getAlias('@webroot').$this->getModule()->avatarPath);
                if($model->photo){
                    $model->updatePhoto($model->photo);
                    return $this->redirect("/user/edit");
                }

                if($model->password != ''){
                    if($data->validatePassword($model->password))
                    {
                        $data->password = $model->new_password;
                        $data->save();
                        Yii::$app->getSession()->setFlash('success', 'Пароль изменен');
                        return $this->redirect("/user/edit");
                    }
                }

                if(Yii::$app->request->get('update') == Edit::SCENARIO_MAIN)
                {
                    $data->updateProfile($data, $model);
                }
            }
            else
            {
                Yii::$app->getSession()->setFlash('error', "Произошла ошибка");
                return $this->redirect("/user/edit");
            }
        }

        return $this->render('edit', ['model' => $model, 'data' => $data]);
    }
}