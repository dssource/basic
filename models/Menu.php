<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\models;

use Yii;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Menu extends ActiveRecord
{
    public $attachmentLevel;
    public $hasChild;

    public static $defaultImage = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OCA0OCI+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSIwIiB4MT0iMTMuNTk0IiB5MT0iMzcuMDkiIHgyPSIxMi42ODkiIHkyPSItMTIuNjY1IiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHN0b3Agc3RvcC1jb2xvcj0iIzU2NjA2OSIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iIzZjNzg4NCIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxwYXRoIGQ9Im0xNi40MjggMTUuNzQ0Yy0uMTU5LS4wNTItMS4xNjQtLjUwNS0uNTM2LTIuNDE0aC0uMDA5YzEuNjM3LTEuNjg2IDIuODg4LTQuMzk5IDIuODg4LTcuMDcgMC00LjEwNy0yLjczMS02LjI2LTUuOTA1LTYuMjYtMy4xNzYgMC01Ljg5MiAyLjE1Mi01Ljg5MiA2LjI2IDAgMi42ODIgMS4yNDQgNS40MDYgMi44OTEgNy4wODguNjQyIDEuNjg0LS41MDYgMi4zMDktLjc0NiAyLjM5Ni0yLjIzOC43MjQtOC4zMjUgNC4zMzItOC4yMjkgOS41ODZoMjQuMDVjLjEwNy01LjAyLTQuNzA4LTguMjc5LTguNTEzLTkuNTg2bTIxLjgxNzMwNS0zLjA3OTE5NmEyNS4zMjk3MTggMjUuMzI5NzE4IDAgMCAxIC0yNS4zMjk3MTggMjUuMzI5NzE4IDI1LjMyOTcxOCAyNS4zMjk3MTggMCAwIDEgLTI1LjMyOTcxOCAtMjUuMzI5NzE4IDI1LjMyOTcxOCAyNS4zMjk3MTggMCAwIDEgMjUuMzI5NzE4IC0yNS4zMjk3MTggMjUuMzI5NzE4IDI1LjMyOTcxOCAwIDAgMSAyNS4zMjk3MTggMjUuMzI5NzE4IiBmaWxsPSJ1cmwoIzApIiB0cmFuc2Zvcm09Im1hdHJpeCguOTQ3NDkgMCAwIC45NDc0OSAxMS43NTkgMTIuMDEpIi8+PC9zdmc+';


    public static function tableName()
    {
        return 'ds_menu';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_parent' => 'Вложить в категорию',
            'label' => 'Подпись',
            'url' => 'Ссылка',
            'accept' => 'Видят',
            'position' => 'Позиция',
            'active' => 'Активная запись'
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->getSession()->setFlash('success', 'Раздел создан');
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function rules()
    {
        return [
            [['id_parent', 'label', 'url', 'accept', 'position', 'active'], 'required'],
            [['id_parent', 'position'], 'integer']
        ];
    }


    public static function findParent()
    {
        return Menu::findAll(['id_parent' => 0]);
    }

    public function getChildrens()
    {
        return $this->hasMany(static::className(), ['id_parent' => 'id']);
    }

    public static function getTreeArrayByChild($objectsChildMenu, $iterationCount = 1)
    {
        foreach($objectsChildMenu as $item)
        {
            if(!is_object($item)) continue;

            $item->attachmentLevel = $iterationCount;
            $item->hasChild = false;

            $childrens = $item->childrens;

            $data[] = $item;

            if(count($childrens) == 0)
            {
                continue;
            }
            else
            {
                $data[count($data)-1]->hasChild = true;
                $data = array_merge($data, self::getTreeArrayByChild($childrens, ($iterationCount+1)));
            }
        }

        return $data;
    }

    public static function getTreeArray()
    {
        return self::getTreeArrayByChild(Menu::findParent());
    }

    public static function getItems()
    {
        return static::getItemsByArray(static::findParent());
    }


    public function allItems()
    {
        return array_merge(["0" => "Не вложена"], ArrayHelper::map(Menu::find()->all(), 'id', 'label'));
    }

    public function acceptArray ()
    {
        return [
            '?' => 'Все',
            '@' => 'Авторизированные пользователи'
        ];
    }
    // Вывод меню: генерация HTML
    public static function generateHTML($source)
    {
        $output = '';
        foreach ($source as $item)
        {
            if(count($item->childrens) == 0)
                $output .= '<li class=""><a href="'.$item->url.'">'.$item->label.'</a></li>';
            else
            {
                $output .= '<li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$item->label.' <b class="caret"></b></a>
                                <ul class="dropdown-menu">';

                $output .= static::generateHTML($item->childrens);

                $output .= '    </ul>
                            </li>';
            }
        }

        return $output;
    }
    // Вывод меню: виджет
    public static function widget($showCabinet = true)
    {
        $menu = '<ul class="nav navbar-nav">'.
        static::generateHTML(static::findParent()).'
                    </ul>';

        if($showCabinet)
                $menu .= '<ul class="nav navbar-nav navbar-right">
              <li>
                <div class="inset hidden-xs">
                  <img src="'.(Yii::$app->user->isGuest ? self::$defaultImage : Yii::$app->user->identity->image).'">
                </div>
                '.(Yii::$app->user->isGuest ? Html::a('Войти', '/user/login', ['class' => 'float-right']) : '<a href="#" class="dropdown-toggle float-right" data-toggle="dropdown">'.Yii::$app->user->identity->fullName.
                ' <b class="caret"></b></a>
                                <ul class="dropdown-menu">'.
                '                   <li>'.Html::a('Профиль', '/user/'.Yii::$app->user->identity->username).'</li>'.
                '                   <li>'.Html::a('Сообщения', '/user/pm').'</li>'.
                '                   '.(Yii::$app->user->can('admin') ? '<li>'.Html::a('Управление', '/admin').'</li>' : '').
                '                   <li>'.Html::a('Выход', '/user/logout').'</li>'.
                '               </ul>').'
              </li>
            </ul>';

        return $menu;
    }

}