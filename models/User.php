<?php

namespace dssource\basic\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $loginUrl = 'user/login';

    public $defaultImage = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OCA0OCI+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSIwIiB4MT0iMTMuNTk0IiB5MT0iMzcuMDkiIHgyPSIxMi42ODkiIHkyPSItMTIuNjY1IiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHN0b3Agc3RvcC1jb2xvcj0iIzU2NjA2OSIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iIzZjNzg4NCIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxwYXRoIGQ9Im0xNi40MjggMTUuNzQ0Yy0uMTU5LS4wNTItMS4xNjQtLjUwNS0uNTM2LTIuNDE0aC0uMDA5YzEuNjM3LTEuNjg2IDIuODg4LTQuMzk5IDIuODg4LTcuMDcgMC00LjEwNy0yLjczMS02LjI2LTUuOTA1LTYuMjYtMy4xNzYgMC01Ljg5MiAyLjE1Mi01Ljg5MiA2LjI2IDAgMi42ODIgMS4yNDQgNS40MDYgMi44OTEgNy4wODguNjQyIDEuNjg0LS41MDYgMi4zMDktLjc0NiAyLjM5Ni0yLjIzOC43MjQtOC4zMjUgNC4zMzItOC4yMjkgOS41ODZoMjQuMDVjLjEwNy01LjAyLTQuNzA4LTguMjc5LTguNTEzLTkuNTg2bTIxLjgxNzMwNS0zLjA3OTE5NmEyNS4zMjk3MTggMjUuMzI5NzE4IDAgMCAxIC0yNS4zMjk3MTggMjUuMzI5NzE4IDI1LjMyOTcxOCAyNS4zMjk3MTggMCAwIDEgLTI1LjMyOTcxOCAtMjUuMzI5NzE4IDI1LjMyOTcxOCAyNS4zMjk3MTggMCAwIDEgMjUuMzI5NzE4IC0yNS4zMjk3MTggMjUuMzI5NzE4IDI1LjMyOTcxOCAwIDAgMSAyNS4zMjk3MTggMjUuMzI5NzE4IiBmaWxsPSJ1cmwoIzApIiB0cmFuc2Zvcm09Im1hdHJpeCguOTQ3NDkgMCAwIC45NDc0OSAxMS43NTkgMTIuMDEpIi8+PC9zdmc+';

    const USER_ACTIVE = 10;
    const USER_DEACTIVATED = 1;
    const USER_NEED_VERIFY = 2;
    const USER_BLOCKED = 0;

    const SEX_MALE = 1;
    const SEX_FEMALE = 2;

    const USER_ACTIVATION_IMPOSSIBLE = 0;

    const SCENARIO_ADMIN = 'SCENARIO_ADMIN';

    const ONLINE_TIME = 600; //sec

    public function getSexTypes()
    {
        return [
            self::SEX_MALE => 'Мужчкой',
            self::SEX_FEMALE => 'Женский'
        ];
    }

    public function getSex($int)
    {
        return (self::getSexTypes()[$int] == '') ? 'не определен' : self::getSexTypes()[$int];
    }

    public function getStatusNames()
    {
        return [
            self::USER_ACTIVE => 'Активный',
            self::USER_DEACTIVATED => 'Не активный',
            self::USER_NEED_VERIFY => 'Ожидает подтверждения',
            self::USER_BLOCKED => 'Заблокирован'
        ];
    }

    public function getStatusName($int)
    {
        if(!isset($this->getStatusNames()[$int])) return "Не определен";
        else
            return $this->getStatusNames()[$int];
    }


    public function rules(){
        return [
            [$this->attributes, 'safe', 'on' =>self::SCENARIO_ADMIN]
        ];
    }

    public static function tableName()
    {
        return 'ds_users';
    }

    public function getStrategy()
    {
        return Yii::$app->getModule('user')->strategy;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if ($this->isNewRecord)
            {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            if($this->created_at == '') $this->created_at = time();
            if($this->actionKey == '') $this->actionKey = self::USER_ACTIVATION_IMPOSSIBLE;
            if($this->password_reset_token == '') $this->password_reset_token = Yii::$app->security->generatePasswordHash($this->username.time().rand(0,999));
            $this->updated_at = time();
            $this->last_active = time();
            return true;
        }
        return false;
    }

    public function setPassword ($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getUserByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    public function setActive()
    {
        if(!$this->isNewRecord)
        {
            //$this->last_active = time();
            //$this->save();
        }
    }

    public function isOnline()
    {
        return ($this->last_active > (time()-self::ONLINE_TIME)) ? true : false;
    }

    public function attributeLabels()
    {
        return [
            'brightDay' => 'День рождения',
            'phone' => 'Телефон',
            'email' => 'Эл. почта',
            'last_active' => 'Последняя активность',
            'status' => 'Статус',
            'created_at' => 'Зарегистрирован'
        ];
    }

    public function getZodiac() {
        $month = date('m', $this->brightDay);
        $day = date('d', $this->brightDay);
        $signs = array("Козерог", "Водолей", "Рыбы", "Овен", "Телец", "Близнецы", "Рак", "Лев", "Девы", "Весы", "Скорпион", "Стрелец");
        $signsstart = array(1=>21, 2=>20, 3=>20, 4=>20, 5=>20, 6=>20, 7=>21, 8=>22, 9=>23, 10=>23, 11=>23, 12=>23);
        return $day < $signsstart[$month + 1] ? $signs[$month - 1] : $signs[$month % 12];
    }
}
