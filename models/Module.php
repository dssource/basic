<?php

namespace dssource\basic\models;


use Yii;
use yii\db\ActiveRecord;


class Module extends ActiveRecord
{
    public static function tableName(){
        return 'ds_modules';
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ID модуля (Латиница)',
            'class' => 'Class',
            'position' => 'Позиция',
            'options' => 'Опции',
            'label' => 'Название',
            'icon' => 'Иконка',
            'is_active' => 'Активный'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'class', 'position', 'label', 'icon'], 'trim'],
            [['position', 'is_active'], 'default', 'value' => 1],
            [['name', 'class', 'position', 'label', 'icon', 'is_active'], 'required'],
            ['options', 'safe']
        ];
    }

    public function findActive()
    {
        return $this->find(['is_active' => 1])->orderBy('position')->all();
    }

    public static function menuActivateItems ($items)
    {
        foreach ($items as $item_id => $item)
        {
            if($item['url'][0] == '/'.Yii::$app->request->pathInfo)
                $items[$item_id]['active'] = true;

            if(is_array($item['items']))
                $items[$item_id]['items'] = self::menuActivateItems($item['items']);
        }

        return $items;
    }

    public function adminVerticalMenu()
    {

        $items = [];

        foreach ($this->findActive() as $model)
        {
            $class = $model->class;
            $classItems = $class::menu();

            if(!$classItems) continue;

            $items = array_merge($items, $classItems);
        }

        $items[] = [
                'label' => '<a href="#"><span class="glyphicon glyphicon-cog"></span> Система</a>',
                'items' => [
                    [
                       'label' => 'Модули',
                        'url' => ['/admin/modules'],
                        //'visible' => Yii::$app->user->isGuest,
                    ],
                    [
                        'label' => 'Сбросить Кэш',
                        'url' => ['/admin/assets-clear'],
                        //'visible' => Yii::$app->user->isGuest,
                    ],
                    [
                        'label' => 'Посмотреть сайт',
                        'url' => ['/'],
                        //'visible' => Yii::$app->user->isGuest,
                    ]
                   ]
            ];

        $items = self::menuActivateItems($items);

        return \yii\widgets\Menu::widget([
            'encodeLabels' => false,
            'items' => $items,
            'options' => [
                'class' => 'admin-left-menu'
            ],
            'route' => '/'.Yii::$app->request->pathInfo,
            'activeCssClass'=>'active',
            'submenuTemplate' => "\n<ul class='admin-left-menu-drop'>\n{items}\n</ul>\n",
        ]);
    }


}
