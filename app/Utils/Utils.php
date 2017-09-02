<?php

if (!function_exists('dd')) {
    /**
     * Debug helper function
     *
     */
    function dd($data = null)
    {
        var_dump($data); die;
    }
}

if (!function_exists('view')) {
    /**
     * View Helper function
     *
     * @param string $view view path
     *
     */
    function view($view, array $params = [])
    {
        $view = str_replace('.', '/', $view);
        ob_start();
        extract($params, EXTR_SKIP);
        require_once APP_PATH . "Views/{$view}.html";
        ob_end_flush();
    }
}

if (!function_exists('config')) {
    /**
     * Debug helper function
     *
     */
    function config($key)
    {
        return \App\Core\Config::get($key);
    }
}
