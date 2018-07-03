<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\models;

use dssource\basic\core\BaseSection;

class Section extends BaseSection {

    public function sections($class = false)
    {
        if(!$class)
            return static::find()->all();
        else
            return static::find()->where(['class' => $class])->all();
    }

}