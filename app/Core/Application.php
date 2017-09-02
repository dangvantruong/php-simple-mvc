<?php

namespace App\Core;


/**
 * Front controller
 *
 * Parser request url and dispatch to coresponding controller action
 */
class Application
{
    const DEFAULT_CONTROLLER = "\App\Controllers\HomeController";
    const DEFAULT_ACTION = 'index';

    private $controller;
    private $action;
    private $params;

    public function __construct()
    {
        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        $pathInfo = explode("/", $path, 3);
        $this->params = [];
        $this->action = 'index';
        $this->controller = self::DEFAULT_CONTROLLER;

        if (count($pathInfo) >= 1) {
            if (isset($pathInfo[0])) {
                $this->setController($pathInfo[0]);
            }

            if (isset($pathInfo[1])) {
                $this->setAction($pathInfo[1]);
            }

            if (isset($pathInfo[2])) {
                $this->params = [$pathInfo[2]];
            }
        }
    }

    public function setController($controller)
    {
        $controller = sprintf("\App\Controllers\%sController", ucfirst($controller));
        if (!class_exists($controller)) {
            throw new \Exception("Controller {$controller} not found!");
        }

        $this->controller = $controller;
        return $this;
    }

    public function setAction($action)
    {
        $reflector = new \ReflectionClass($this->controller);
        if (!$reflector->hasMethod($action)) {
            throw new \Exception("Controller {$this->controller} action: {$action} not found!");
        }

        $this->action = $action;
        return $this;
    }


    /**
     * Dispath request to coresponding controller action with params
     *
     */
    public function run()
    {
        call_user_func_array(array(new $this->controller, $this->action), $this->params);
    }

}
