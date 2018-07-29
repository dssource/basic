<?php

use yii\db\Migration;

class m000000_000007_user extends Migration
{
    public $tableName = 'ds_users';
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'username' => $this->string(30)->notNull()->unique(),
            'name' => $this->text(),
            'surname' => $this->text(),
            'last_name' => $this->text(),
            'last_name' => $this->text(),
            'brightDay' => $this->integer(),
            'phone' => $this->text(),
            'photo' => $this->text(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'last_active' => $this->integer(),
            'status' => $this->integer(),
            'actionKey' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Add admin account (admin:admin)

        $this->insert($this->tableName, [
            'id' => 1,
            'username' => 'admin',
            'name' => 'John',
            'surname' => 'Doe',
            'last_name' => 'Smith',
            'auth_key' => 'BmhS7in4X70H_wxMH0xUNBV-k4DH2d4I',
            'password_hash' => '$2y$13$ud4aiqGwFBNJlb8FPASs1.U.D9kuu8ZSyCZjdAoGyhakp695wtljC', // admin
            'password_reset_token' => '',
            'email' => 'admin@localhost.loc',
            'status' => 10,
            'actionKey' => 0,
            'created_at' => 1523447259,
            'updated_at' => 1523447259
        ]);

        Yii::$app->authManager->removeAll();

        $developer = Yii::$app->authManager->createRole('developer');
        $developer->description = 'Разработчик';
        Yii::$app->authManager->add($developer);

        $admin = Yii::$app->authManager->createRole('admin');
        $admin->description = 'Администратор';
        Yii::$app->authManager->add($admin);

        $user = Yii::$app->authManager->createRole('user');
        $user->description = 'Пользователь';
        Yii::$app->authManager->add($user);

        Yii::$app->authManager->addChild($admin, $user);
        Yii::$app->authManager->addChild($developer, $admin);

        Yii::$app->authManager->assign(Yii::$app->authManager->getRole('developer'), 1);



    }
    public function down()
    {
        $this->truncateTable($this->tableName);
        $this->dropTable($this->tableName);
        Yii::$app->authManager->removeAll();
        /*
        // auth_assignment
        $this->truncateTable('auth_assignment');
        $this->dropTable('auth_assignment');
        // auth_item
        $this->truncateTable('auth_item');
        $this->dropTable('auth_item');
        // auth_item_child
        $this->truncateTable('auth_item_child');
        $this->dropTable('auth_item_child');
        // auth_rule
        $this->truncateTable('auth_rule');
        $this->dropTable('auth_rule');
        */
    }
}