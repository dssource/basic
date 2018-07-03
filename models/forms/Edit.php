<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\models\forms;

use dssource\user\models\User;
use yii\base\Model;
use Yii;


class Edit extends Model
{
    public $name;
    public $surname;
    public $last_name;

    public $password;
    public $new_password;
    public $repeat_new_password;
    public $email;
    public $photo;
    public $phone;

    public $brightDay;

    const SCENARIO_PHOTO = 'photo';
    const SCENARIO_MAIN = 'main';
    const SCENARIO_PASSWORD = 'password';
    const SCENARIO_CHANGE_EMAIL = 'email';

    public function getModule()
    {
        return Yii::$app->getModule('user');
    }

    public function getStrategy()
    {
        return Yii::$app->getModule('user')->strategy;
    }


    public function rules()
    {
        return [
            [['name', 'surname', 'last_name', 'phone'], 'trim', 'on' => self::SCENARIO_MAIN],
            [['name', 'surname', 'last_name', 'phone'], 'string', 'min' => 3, 'max' => 255, 'on' => self::SCENARIO_MAIN],
            ['phone', 'match', 'pattern' => '/^\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => ' Ввидите номер в формате+7 999 999 99 99', 'skipOnEmpty' => true, 'on' => self::SCENARIO_MAIN],
            ['brightDay', 'match', 'pattern' => '/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/', 'skipOnEmpty' => true, 'on' => self::SCENARIO_MAIN],
            //['day', 'in', 'on' => self::SCENARIO_MAIN],

            ['email', 'email', 'on' => self::SCENARIO_CHANGE_EMAIL],
            [['email'], 'unique', 'targetClass' => 'dssource\user\models\User', 'message' => 'Это значение уже используется в системе', 'on' => self::SCENARIO_CHANGE_EMAIL],

            [['password', 'new_password', 'repeat_new_password'], 'required', 'on' => self::SCENARIO_PASSWORD],
            ['repeatPassword', 'compare', 'compareAttribute' => 'new_password', 'on' => self::SCENARIO_PASSWORD],

            ['photo', 'image','extensions' => 'jpg,png,gif', 'skipOnEmpty' => true, 'on' => self::SCENARIO_PHOTO],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилие',
            'last_name' => 'Отчество',
            'password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'repeat_new_password' => 'Повторите пароль',
            'email' => 'Электронная почта',
            'phone' => 'Телефон',
            'photo' => 'Фотография',
            'brightDay' => 'День рождения',
        ];
    }

    public function updatePhoto($PhotoObject)
    {
        $currentUserId = Yii::$app->user->identity->getId();
        $path = $this->getModule()->avatarPath.$currentUserId.'.'.$PhotoObject->extension;

        if($PhotoObject->saveAs(Yii::getAlias('@webroot').$path))
        {
            $user = User::findOne($currentUserId);
            $user->photo = $path;
            $user->save();

            Yii::$app->getSession()->setFlash('success', 'Фотография обновлена');
        }
        else
            Yii::$app->getSession()->setFlash('error', 'При загрузке фото произошла ошибка');
    }

    public function updateProfile($userObj, $dataObj)
    {
        $userObj->name = $dataObj->name;
        $userObj->surname = $dataObj->surname;
        $userObj->last_name = $dataObj->last_name;
        $userObj->brightDay = strtotime($dataObj->brightDay);
    }

}