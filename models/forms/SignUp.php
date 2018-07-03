<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\models\forms;

use Yii;
use yii\base\Model;
use dssource\basic\models\User;


class SignUp extends Model
{
    public $username;
    public $password;
    public $repeatPassword;
    public $email;
    public $verifyCode;

    public function getStrategy()
    {
        return Yii::$app->getModule('user')->strategy;
    }

    public function scenarios()
    {
        $sc = parent::scenarios();
        $sc['SCENARIO_FAST'] = ['username', 'password', 'repeatPassword', 'email','verifyCode'];
        $sc['SCENARIO_EMAIL'] = ['username', 'email','verifyCode'];
        $sc['SCENARIO_MANAGER'] = ['username', 'password', 'repeatPassword', 'email','verifyCode'];

        return $sc;
    }

    public function rules()
    {
        return [
            [['username', 'password', 'repeatPassword', 'email'], 'trim'],
            [['username', 'password', 'repeatPassword', 'email'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'unique', 'targetClass' => 'dssource\basic\models\User', 'message' => 'Это значение уже используется в системе'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password'],
            ['verifyCode', 'captcha', 'captchaAction' => '/user/user/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'repeatPassword' => 'Повторите пароль',
            'email' => 'Электронная почта',
            'verifyCode' => 'Код с картинки',
        ];
    }

    public function fastCreate ($username, $email, $password)
    {
        $newUser = new User();
        $newUser->username = $username;
        $newUser->email = $email;
        $newUser->password = $password;
        $newUser->status = User::USER_ACTIVE;
        $newUser->save();

        Yii::$app->user->login($newUser, 3600*24*30);

        return $newUser;
    }

    public function manageCreate ($username, $email, $password)
    {
        $newUser = new User();
        $newUser->username = $username;
        $newUser->email = $email;
        $newUser->password = $password;
        $newUser->status = User::USER_NEED_VERIFY;
        $newUser->save();

        //Yii::$app->user->login($newUser, 3600*24*30);
        Yii::$app->getSession()->setFlash('info', 'Спасибо! Ваша учетная запись будет активирована, после ее проверки администрацией сайта. По результатам проверки мы уведовим Вас по электронной почте');
        return $newUser;
    }

    public function emailCreate($username, $email)
    {
        $newUser = new User();
        $newUser->status = User::USER_DEACTIVATED;
        $newUser->actionKey = \Yii::$app->security->generateRandomString();
        $newUser->username = $username;
        $newUser->email = $email;
        $_pw =  Yii::$app->getSecurity()->generateRandomString();
        $newUser->password = $_pw;
        $newUser->save();

        $message = Yii::$app->getModule('user')
            ->mail
            ->compose('sendPassword', [
                'model' => $newUser,
                'password' => $_pw
            ]);
        $message->setFrom(Yii::$app->params['robotEmail'] ? Yii::$app->params['robotEmail'] : 'robot@'.getenv('HTTP_HOST'));
        $message->setTo($newUser->email);
        $message->setSubject("Подтверждение регистрации");
        $message->send();
    }

}