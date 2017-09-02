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
        return view($view, $params);
    }
}
