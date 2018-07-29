<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\core;

use Yii;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;

class BaseSection extends ActiveRecord
{
    public $imageFile;
    public $isParent;
    private $_cache;


    const NO_IMAGE = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iNjI0IiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDYyNCA1MTIiPjxnIGlkPSJpY29tb29uLWlnbm9yZSI+PC9nPjxwYXRoIGZpbGw9IiNFNUUzRTMiIGQ9Ik0yLjc0NyAzLjU2NnY1MDQuODdoNjIxLjM3OXYtNTA0Ljg3aC02MjEuMzc5ek01ODUuMjg5IDQ2OS41OTloLTU0My43MDZ2LTQyNy4xOTdoNTQzLjcwNnY0MjcuMTk3ek00MjkuOTQ1IDEzOS40OTJjMCAzMi4xNzMgMjYuMDgwIDU4LjI1NSA1OC4yNTUgNTguMjU1czU4LjI1NC0yNi4wODEgNTguMjU0LTU4LjI1NS0yNi4wODEtNTguMjU1LTU4LjI1NS01OC4yNTUtNTguMjU1IDI2LjA4MS01OC4yNTUgNTguMjU1ek01NDYuNDUzIDQzMC43NjJoLTQ2Ni4wMzRsMTE2LjUwOC0zMTAuNjg5IDE1NS4zNDUgMTk0LjE4MSA3Ny42NzItNTguMjU1IDExNi41MDkgMTc0Ljc2MnoiPjwvcGF0aD48L3N2Zz4=';

    public static function tableName()
    {
        return 'ds_site_sections';
    }

    public function beforeSave($insert)
    {
        $this->class = self::className();
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->class = $this->className();
    }

    public static function checkClassName()
    {
        return self::className();
    }

    /**
     * @param bool|false $condition
     * @return $this
     */
    public static function find($condition = false)
    {
        return $condition ? parent::find()->where(['class' => self::className()])->andWhere($condition) : parent::find()->where(['class' => self::className()]);
    }

    /*                               GETTERS
    */
    /**
     * @param $field
     * @param $id
     * @return bool
     */
    public function getFieldById($field, $id)
    {
        if(isset($this->_cache[$id])) return $this->_cache[$id]->attributes[$field];
        else
        {
            $item = static::findOne($id);
            if($item == null) return false;
            else
            {
                $this->_cache[$id] = $item;
                return $item->attributes[$field];
            }
        }
    }

    /**
     * @return bool
     */
    public function getIsParent()
    {
        if($this->id_parent == 0 or $this->id == 1) return true;
        else return false;
    }
    /**
     * @return mixed|string
     */
    public function getImageUrl()
    {
        if($this->image != '')
            return (substr($this->image,0,4) == 'data' ? $this->image : '/files/sections/'.Yii::getAlias('@web').$this->image);
        else
            return self::NO_IMAGE;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return '/'.$this->getFullUrlPath();
    }

    /**
     * @return string
     */
    public function getFullUrlPath()
    {
        return $this->getFullPathReturnAttribute('alias');
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        return $this->getFullPathReturnAttribute('name');
    }

    /**
     * @param $attribute
     * @return string
     */
    public function getFullPathReturnAttribute($attribute)
    {
        //return 'Отключено в ядре модели';

        if($this->getIsParent())
            return $this->attributes[$attribute];
        else
        {
            $parentSection = self::findOne($this->id_parent);
            //exit(var_dump($this->id_parent));

            return $parentSection->getFullPathReturnAttribute($attribute).'/'.$this->attributes[$attribute];
        }
    }

    /**
     * @return array
     */
    public function getBreadcrumbsArray()
    {
        //return ['label' => 'отключено в ядре модели', 'url' => '#'];
        $breadcrumbs[] = ['label' => Html::encode($this->name), 'url' => $this->url];

        if($this->getIsParent())
            return [];
        else
        {
            $sectionParent = static::findOne($this->id_parent);
            //$breadcrumbs[] = ['label' => Html::encode($sectionParent->name), 'url' => $sectionParent->url];
            if(!$sectionParent->isParent) $breadcrumbs = array_merge($breadcrumbs, $sectionParent->getBreadcrumbsArray());

            return $breadcrumbs;
        }
    }

    public function getBreadcrumbs($full = true)
    {
        $data = array_reverse($this->getBreadcrumbsArray());
        if(!$full)
        {
            unset($data[count($data)-1]);
        }
        return $data;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'isParent' => 'Родительская категория',
            'id_parent' => 'Вложить в категорию',
            'image' => 'Картинка',
            'imageFile' => 'Картинка',
            'name' => 'Имя',
            'alias' => 'Алиас (URL)',
            'class' => 'PHP Class',
            'description' => 'Описание',
            'show_in_catalog' => 'Отображать в каталоге',
            'default_item_id' => 'Элемент по умолчанию',
            'options' => 'Опции (название=значение;...)',
        ];
    }

    public function uploadImage()
    {
        $newName = $this->imageFile->baseName . '.' . $this->imageFile->extension;

        $newName = date(dmyhis).'-'.$newName;

        $this->imageFile->saveAs(Yii::getAlias('@webroot').'/'.$this->uploadImagePath.'/'.$newName);
        $this->image = $newName;
        return true;
    }

}