<?php

use yii\db\Migration;

class m220228_125709_storage_rule_rbac extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        $rule = $auth->getRule('WorkspaceCheckRule');

        $role = Yii::$app->setting->getValue('site::admin_role');
        $admin = (isset($role) && $role != '') ? $auth->getRole($role) : $auth->getRole('admin');
        $endPrefix = 'ForWorkspace';
        $permissions = [
            'storageApiDefaultView' => 'Storage Api Default View',
            'storageApiDefaultCreate' => 'Storage Api Default Create',
            'storageApiDefaultUpdate' => 'Storage Api Default Update',
            'storageApiDefaultDelete' => 'Storage Api Default Delete',
            'storageApiDefaultIndex' => 'Storage Api Default Index',
            'storageWebDefaultIndex' => 'Storage Web Default Index',
            'storageWebDefaultView' => 'Storage Web Default View',
            'storageWebDefaultCreate' => 'Storage Web Default Create',
            'storageWebDefaultUpdate' => 'Storage Web Default Update',
            'storageWebDefaultDelete' => 'Storage Web Default Delete',

        ];


        foreach ($permissions as $permissionKey => $permissionDescription) {
            $permissionForWorkspace = $auth->createPermission($permissionKey . $endPrefix);
            $permissionForWorkspace->description = ' (' . $endPrefix . ')' . $permissionDescription;
            $permissionForWorkspace->ruleName = $rule->name;
            $auth->add($permissionForWorkspace);
            $auth->addChild($admin, $permissionForWorkspace);
            $permission = $auth->getPermission($permissionKey);
            $auth->addChild($permissionForWorkspace, $permission);

        }
    }

    public function down()
    {
        $auth = Yii::$app->authManager;
        $endPrefix = 'ForWorkspace';
        $permissions = [
            'storageApiDefaultView',
            'storageApiDefaultCreate',
            'storageApiDefaultUpdate',
            'storageApiDefaultDelete',
            'storageApiDefaultIndex',
            'storageWebDefaultIndex',
            'storageWebDefaultView',
            'storageWebDefaultCreate',
            'storageWebDefaultUpdate',
            'storageWebDefaultDelete',
        ];

        foreach ($permissions as $permission) {
            $permissionForWorkspace = $auth->getPermission($permission . $endPrefix);
            $auth->remove($permissionForWorkspace);
        }
    }
}
