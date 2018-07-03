<?php
/**
 * Created by Digital-Solution.Ru web-studio.
 * https://digital-solution.ru
 * support@digital-solution.ru
 */

namespace dssource\basic\models\forms;

use Codeception\Exception\ElementNotFound;
use Yii;
use yii\base\Model;
use yii\rbac\Role;
use yii\rbac\Permission;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class RbacRole extends Model
{
    public $name;
    public $description;
    public $permission;

    public function rules(){
        return [
            [['name', 'description', 'permission'], 'required'],
            [['name'], 'match', 'pattern' => '/^[a-z0-9_-]+$/'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'description' => 'Описание',
            'permission' => 'Права'
        ];
    }

    /**
     * @param Rbac Object authManager->getRole() $role
     * @param Array of authManager->getPermission() $permission
     */
    public function setPermission($role, $permission)
    {

        // Устанавливает роли $role полномочия $permissions
        foreach ($permission as $permit) { // Перебор полномочий
            // Получаем полномочие
            $new_permit = Yii::$app->authManager->getPermission($permit);
            // Если оно еще не добавлено добавляем
            if(!Yii::$app->authManager->hasChild($role, $new_permit))
                Yii::$app->authManager->addChild($role, $new_permit);
        }
        // Получаем список имеющихся полномочий
        $oldPermissions = Yii::$app->authManager->getPermissionsByRole($role->name);
        // Перебираем
        if(count($oldPermissions) > 0)
            foreach ($oldPermissions as $oldPermission)
                // Если полномочия нет среди добавленных - убираем
                if(!in_array($oldPermission->name, $permission))
                {
                    Yii::$app->authManager->removeChild($role, $oldPermission);
                }
    }
    public function add()
    {
        $new = Yii::$app->authManager->createRole($this->name);
        $new->description = $this->description;
        Yii::$app->authManager->add($new);

        $this->setPermission($new, $this->permission);

        Yii::$app->getSession()->setFlash("success", "Полномочие добавлено");
    }

    public function update($name)
    {
        $new = Yii::$app->authManager->getRole($name);
        if($new == null)
            throw new NotFoundHttpException("Роль не найдена");


        if(!$this->isUnique($this->name) && $name != $this->name)
            throw new ForbiddenHttpException("Это имя уже зарезервировано");

        $new->name = $this->name;
        $new->description = $this->description;
        Yii::$app->authManager->update($name, $new);

        $this->setPermission(Yii::$app->authManager->getRole($new->name), $this->permission);

        Yii::$app->getSession()->setFlash("success", "Роль обновлена");
    }

    public function delete($name)
    {
        $permit = Yii::$app->authManager->getRole($name);
        if ($permit) {
            Yii::$app->authManager->remove($permit);
            Yii::$app->getSession()->setFlash("info", "<strong>Роль удалена</strong>");

            return true;
        }
        else
            return false;
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