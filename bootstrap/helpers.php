<?php
    
    function route_class()
    {
        return str_replace('.', '-', Route::currentRouteName());
    }

    function make_excerpt($value, $length = 200)
    {
        $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
        return str_limit($excerpt, $length);
    }
    if (!function_exists('manage_contents')) {
        function manage_contents() {
        // 只要是能管理内容的用户，就允许访问后台
        return Auth::check() && Auth::user()->can('manage_contents');
        }
    }
    if (!function_exists('manage_users')) {
        function manage_users()
        {
            return Auth::check() && Auth::user()->can('manage_users');
        }
    }

    if (!function_exists('administrator_users_avatar')) {
        function administrator_users_avatar($avatar, $model)
        {
            return empty($avatar) ? 'N/A' : '<img src="'.$avatar.'" width="40">';
        }
    }

    if (!function_exists('administrator_users_name')) {
        function administrator_users_name($name, $model)
        {
            return '<a href="/users/'.$model->id.'" target=_blank>'.$name.'</a>';
        }
    }

    if (!function_exists('administrator_roles_permission_output')) {
        function administrator_roles_permission_output($value, $model) {
                $model->load('permissions');
                $result = [];
                foreach ($model->permissions as $permission) {
                    $result[] = $permission->name;
                }

                return empty($result) ? 'N/A' : implode($result, ' | ');
        }
    }

    if (!function_exists('administrator_roles_operation_output')) {
        function administrator_roles_operation_output($value, $model) {
                return $value;
        }
    }

    if (!function_exists('administrator_permission_action_permissions_create')) {
        function administrator_permission_action_permissions_create($model) {
            return true;
        }
    }

    if (!function_exists('administrator_permission_action_permissions_update')) {
        function administrator_permission_action_permissions_update($model) {
            return true;
        }
    }

    if (!function_exists('administrator_permission_action_permissions_delete')) {
        function administrator_permission_action_permissions_delete($model) {
            return false;
        }
    }

    if (!function_exists('administrator_permission_action_permissions_view')) {
        function administrator_permission_action_permissions_view($model) {
            return true;
        }
    }