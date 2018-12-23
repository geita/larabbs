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