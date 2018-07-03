<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\models\forms;

use Yii;
use yii\base\Model;
use yii\rbac\Role;
use yii\rbac\Permission;

class RbacPermission extends Model
{
    public $name;
    public $description;

    public function rules(){
        return [
            [['name', 'description'], 'required'],
            [['name'], 'match', 'pattern' => '/^[a-zA-Z0-9_\/-]+$/'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'description' => 'Описание',
        ];
    }

    public function add()
    {
            $permit = Yii::$app->authManager->createPermission($this->name);
            $permit->description = $this->description;
            Yii::$app->authManager->add($permit);
            Yii::$app->getSession()->setFlash("success", "Полномочие добавлено");
    }


    public function delete($name)
    {
        $permit = Yii::$app->authManager->getPermission($name);
        if ($permit) {
            Yii::$app->authManager->remove($permit);
            Yii::$app->getSession()->setFlash("info", "<strong>Полномочие удалено</strong>");
            return true;
        }
        else
            return false;

    }

    public function update($name)
    {
        $new = Yii::$app->authManager->getPermission($name);
        $new->name = $this->name;
        $new->description = $this->description;
        Yii::$app->authManager->update($name, $new);
        //Yii::$app->authManager->add($new);

        Yii::$app->getSession()->setFlash("success", "Информация обновлена");
    }

    public function isUnique($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        $permission = Yii::$app->authManager->getPermission($name);
        if ($permission instanceof Permission) {
            return false;
        }
        if ($role instanceof Role) {
            return false;
        }
        return true;
    }
}