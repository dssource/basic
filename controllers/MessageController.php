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

class MessageController extends Controller
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


}