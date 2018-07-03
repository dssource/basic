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

    public static function getItemsByArray($sourceArray)
    {
        $finalArray = [];

        foreach($sourceArray as $item)
        {
            $currentItem = [
                'label' => $item->label,
                'url' => $item->url
            ];

            if(count($item['childrens']) >0)
            {
                if($item->id_parent > 0)
                {
                    $currentItem['label'] .= '<b class="caret"></b>';
                    $currentItem['url'] .= '#';
                    $currentItem['linkTemplate'] .= 'XUI';
                }

                $currentItem['itemsOptions'] = ['class'=>'dropdown-menu', 'id' => 'osn'.$item->id];
                $currentItem['submenuOptions'] = ['class'=>'dropdown-menu', 'id' => 'sub'.$item->id];
                $currentItem['items'] = static::getItemsByArray($item['childrens']);
            }

            $finalArray[] = $currentItem;
        }

        return $finalArray;
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

}