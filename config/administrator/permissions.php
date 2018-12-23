<?php

use Spatie\Permission\Models\Permission;

return [
    'title'   => '权限',
    'single'  => '权限',
    'model'   => Permission::class,

    'permission' => 'manage_users',

    // 对 CRUD 动作的单独权限控制，通过返回布尔值来控制权限。
    'action_permissions' => [
        // 控制『新建按钮』的显示
        'create' => 'administrator_permission_action_permissions_create',
        // 允许更新
        'update' => 'administrator_permission_action_permissions_update',
        // 不允许删除
        'delete' => 'administrator_permission_action_permissions_delete',
        // 允许查看
        'view' => 'administrator_permission_action_permissions_view',
    ],

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title'    => '标示',
        ],
        'operation' => [
            'title'    => '管理',
            'sortable' => false,
        ],
    ],

    'edit_fields' => [
        'name' => [
            'title' => '标示（请慎重修改）',

            // 表单条目标题旁的『提示信息』
            'hint' => '修改权限标识会影响代码的调用，请不要轻易更改。'
        ],
        'roles' => [
            'type' => 'relationship',
            'title' => '角色',
            'name_field' => 'name',
        ],
    ],

    'filters' => [
        'name' => [
            'title' => '标示',
        ],
    ],
];