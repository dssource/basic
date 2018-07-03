<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\models\forms;

use Yii;
use dssource\basic\models\User;
use yii\base\Model;

class SignIn extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    public function getStrategy()
    {
        return Yii::$app->getModule('user')->strategy;
    }

    public function rules(){
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean']
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }


    public function login()
    {
        $user = User::getUserByUsername($this->username);

        if($user != null AND $user->validatePassword($this->password) AND $user->status == User::USER_ACTIVE)
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }
        else
        {
            return false;
        }
    }
}