<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\core;

use Yii;
use yii\db\ActiveRecord;

class Themes extends ActiveRecord
{
    public static function tableName()
    {
        return 'ds_themes';
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'path' => 'Путь',
            'options' => 'Опции',
            'description' => 'Описание',
            'author' => 'Автор',
            'version' => 'Версия'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'path'], 'required'],
            [['options', 'description', 'author', 'version'], 'safe']
        ];
    }

    public static function activeThemeObject()
    {
        $theme = Themes::findOne(['active' => true]);

        if($theme == null)
        {
            return new Exception("Не найдено ни одной активной темы");
        }
        else
            return $theme;
    }

    public static function activeTheme()
    {
        return static::activeThemeObject()->path;
    }

    public static function activeThemeRootPath()
    {
        return Yii::getAlias('@app').'/themes/'.static::activeTheme();
    }


    public function activate()
    {
        $old = static::activeThemeObject();
        $old->active = false;
        $old->save();

        $this->active = true;
        $this->save();

        Yii::$app->getSession()->setFlash('success', 'Установлена новая тема');
    }
}