<?php

namespace dssource\basic\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use dssource\basic\UserModule;
use dssource\basic\models\forms\RbacPermission;
use dssource\basic\models\forms\RbacRole;



class AdminRbacController extends Controller
{
    private $_strategy;

    public function getStrategy()
    {
        if($this->_strategy != null) return $this->_strategy;
        else
        {
            $this->_strategy = Yii::$app->getModule('user')->strategy;
            return $this->_strategy;
        }
    }

   public function actionIndex()
   {
       $permissions = ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'description');

       $rbacRoleForm = new RbacRole();
       $rbacPermissionForm = new RbacPermission();

       return $this->render("index", [
           'rbacRoleForm' => $rbacRoleForm,
           'rbacPermissionForm' => $rbacPermissionForm,
           'permissions' => $permissions
       ]);
   }

    public function actionCreate($item){
        switch ($item):
            case 'role':
                $role = new RbacRole();
                if($role->load(Yii::$app->request->post()) && $role->validate())
                {
                    $role->add();
                }
                else
                {
                    foreach ($permission->errors as $error)
                    {
                        if(is_array($error))
                            $st .= implode(", ", $error). "<br>";
                        else
                            $st .= $error.'<br>';
                    }

                    Yii::$app->getSession()->setFlash("danger", "Ошибка: ".$st);
                }
                break;

            case 'permission':
                $permission = new RbacPermission();

                if($permission->load(Yii::$app->request->post()) && $permission->validate())
                {
                    $permission->add();
                }
                else
                {
                    //exit(var_dump(Yii::$app->request->post()));
                    foreach ($permission->errors as $error)
                    {
                        if(is_array($error))
                            $st .= implode(", ", $error). "<br>";
                        else
                            $st .= $error.'<br>';
                    }

                    Yii::$app->getSession()->setFlash("danger", "<strong>Ошибка:</strong> ".$st);
                }

                break;
        endswitch;

        return $this->redirect('/admin/rbac');
    }

    public function actionDeletePermission($name){
        RbacPermission::delete($name);
        return $this->redirect('/admin/rbac');
    }

    public function actionDeleteRole($name){
        RbacRole::delete($name);
        return $this->redirect('/admin/rbac');
    }

    public function actionUpdateRole($name)
    {
        $roleForm = new RbacRole();
        $role = Yii::$app->authManager->getRole($name);

        $permissions = ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'description');
        $role_permit = array_keys(Yii::$app->authManager->getPermissionsByRole($name));



        if($role == null)
        {
            return new NotFoundHttpException("Такой роли не существует");
        }
        else
        {
            if($roleForm->load(Yii::$app->request->post()) && $roleForm->validate())
            {
                $roleForm->update($name);
                return $this->redirect('/admin/rbac/update-role/'.$roleForm->name);
            }
            else
            return $this->render('update-role', [
                'roleForm' => $roleForm,
                'role' => $role,
                'permissions' => $permissions,
                'role_permit' => $role_permit,
            ]);
        }
    }

    public function actionUpdatePermission($name)
    {
        $permissionForm = new RbacPermission();
        $permission = Yii::$app->authManager->getPermission($name);

        if($permission == null)
        {
            return new NotFoundHttpException("Такого полномочия не существует");
        }
        else
        {
            if($permissionForm->load(Yii::$app->request->post()) && $permissionForm->validate())
            {
                $permissionForm->update($name);
                return $this->redirect('/admin/rbac/update-permission/'.$permissionForm->name);
            }
            else
                return $this->render('update-permission', [
                    'permissionForm' => $permissionForm,
                    'permission' => $permission,
                ]);
        }
    }

    public function actionInheritance($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        if($role == null)
        {
            return new NotFoundHttpException("Такого полномочия не существует");
        }
        if(Yii::$app->request->post('inheritance') != '')
        {
            $newRole = Yii::$app->authManager->getRole(Yii::$app->request->post('inheritance'));
            if($newRole!= null){
                if(!Yii::$app->authManager->hasChild($role, $newRole))
                {
                    Yii::$app->authManager->addChild($role, $newRole);
                    Yii::$app->getSession()->setFlash('success', 'Роль добавлена');
                }
                else
                    Yii::$app->getSession()->setFlash('danger', 'Роль уже добавлена');
            }
            else
                Yii::$app->getSession()->setFlash('danger', 'Роль не найдена');

        }

        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(),'name', 'name');
        unset($roles[$name]);

        //exit(var_dump($roles));

        return $this->render('inheritance', [
            'role' => $role,
            'roles' => $roles
        ]);
        exit(var_dump($roles));
    }

    public function actionUnInheritance($rbacItemParent, $rbacItemChild){
       $object1 = Yii::$app->authManager->getRole($rbacItemParent);
        if($object1 == null)
            $object1 = Yii::$app->authManager->getPermission($rbacItemParent);
        if($object1 == null) throw new ForbiddenHttpException("Не определена сущность ".$rbacItemParent);

        $object2 = Yii::$app->authManager->getRole($rbacItemChild);
        if($object2 == null)
            $object2 = Yii::$app->authManager->getPermission($rbacItemChild);
        if($object2 == null) throw new ForbiddenHttpException("Не определена сущность ".$rbacItemChild);

        Yii::$app->authManager->removeChild($object1, $object2);
        Yii::$app->getSession()->setFlash('info', 'Отменена связь <strong>'.$rbacItemChild.'</strong> от <strong>'.$rbacItemParent.'</strong>');

        return $this->redirect('/admin/rbac');

    }
}