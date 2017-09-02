<?php

namespace App\Controllers;

/**
 * Base controller
 *
 */
class Controller
{
    /**
     * View Helper function
     *
     * @param string $view view path
     *
     */
    function render($view, array $params = [])
    {
        $view = str_replace('.', '/', $view);
        ob_start();
        extract($params, EXTR_SKIP);
        require_once APP_PATH . "Views/{$view}.html";
        ob_end_flush();
    }
}
