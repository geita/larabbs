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

    if (!function_exists('administrator_categories_action_permissions_delete')) {
        function administrator_categories_action_permissions_delete($model) {
            // 只有站长才能删除话题分类
            return Auth::user()->hasRole('Founder');
        }
    }

    if (!function_exists('administrator_topics_columns_title_output')) {
        function administrator_topics_columns_title_output($value, $model) {
                return '<div style="max-width:260px">' . model_link(e($value), $model) . '</div>';
        }
    }


    if (!function_exists('administrator_topics_columns_user_output')) {
        function administrator_topics_columns_user_output($value, $model) {
                $avatar = $model->user->avatar;
                $value = empty($avatar) ? 'N/A' : '<img src="'.$avatar.'" style="height:22px;width:22px"> ' . $model->user->name;
                return model_link($value, $model->user);
        }
    }

    if (!function_exists('administrator_topics_columns_category_output')) {
        function administrator_topics_columns_category_output($value, $model) {
                return model_admin_link($model->category->name, $model->category);
        }
    }

    function model_admin_link($title, $model)
    {
        return model_link($title, $model, 'admin');
    }

    function model_link($title, $model, $prefix = '')
    {
        // 获取数据模型的复数蛇形命名
        $model_name = model_plural_name($model);

        // 初始化前缀
        $prefix = $prefix ? "/$prefix/" : '/';

        // 使用站点 URL 拼接全量 URL
        $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

        // 拼接 HTML A 标签，并返回
        return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
    }

    function model_plural_name($model)
    {
        // 从实体中获取完整类名，例如：App\Models\User
        $full_class_name = get_class($model);

        // 获取基础类名，例如：传参 `App\Models\User` 会得到 `User`
        $class_name = class_basename($full_class_name);

        // 蛇形命名，例如：传参 `User`  会得到 `user`, `FooBar` 会得到 `foo_bar`
        $snake_case_name = snake_case($class_name);

        // 获取子串的复数形式，例如：传参 `user` 会得到 `users`
        return str_plural($snake_case_name);
    }

    if (!function_exists('administrator_replies_columns_content_output')) {
       function administrator_replies_columns_content_output($value, $model) {
                return '<div style="max-width:220px">' . $value . '</div>';
        }
    }

    if (!function_exists('administrator_replies_columns_user_output')) {
       function administrator_replies_columns_user_output($value, $model) {
                $avatar = $model->user->avatar;
                $value = empty($avatar) ? 'N/A' : '<img src="'.$avatar.'" style="height:22px;width:22px"> ' . $model->user->name;
                return model_link($value, $model->user);
        }
    }

    if (!function_exists('administrator_replies_columns_topic_output')) {
       function administrator_replies_columns_topic_output($value, $model) {
                return '<div style="max-width:260px">' . model_admin_link(e($model->topic->title), $model->topic) . '</div>';
        }
    }

    if (!function_exists('administrator_settings_site_permission')) {
        function administrator_settings_site_permission() {
            return Auth::user()->hasRole('Founder');
        }
    }

    if (!function_exists('administrator_settings_site_before_save')) {
        function administrator_settings_site_before_save(&$data)
        {
            // 为网站名称加上后缀，加上判断是为了防止多次添加
            if (strpos($data['site_name'], 'Powered by LaraBBS') === false) {
                $data['site_name'] .= ' - Powered by LaraBBS';
            }
        }
    }

    if (!function_exists('administrator_settings_site_action_action')) {
        function administrator_settings_site_action_action(&$data)
            {
                \Artisan::call('cache:clear');
                return true;
            }
    }

    if (!function_exists('administrator_link_permissions')) {
        function administrator_link_permissions() {
            // 只有站长才能删除话题分类
            return Auth::user()->hasRole('Founder');
        }
    }


