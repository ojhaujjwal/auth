<?php

namespace Auth\Model;

class AuthOrNot
{
    protected $modules = array();

    protected $controllers = array(
        'include' => array(),
        'exclude' => array(),
    );

    protected $requestController;

    public function setModules(array $modules)
    {
        $this->modules = $modules;
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function setControllers(array $controllers)
    {
        $this->controllers = $controllers; 
        $this->controllers['include'] = isset($this->controllers['include']) ? $this->controllers['include'] : array();
        $this->controllers['exclude'] = isset($this->controllers['exclude']) ? $this->controllers['exclude'] : array();
    }

    public function getControllers()
    {
        return $this->controllers;
    }

    public function setRequestController($requestController)
    {
        $this->requestController = $requestController;
    }

    public function getRequestController()
    {
        return $this->requestController;
    }

    public function isLoginRequired()
    {
        $controllerClass = get_class($this->getRequestController());
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        if (!in_array($moduleNamespace, $this->getModules()) && !in_array($controllerClass, $this->getControllers()['include'])) {
            return false;
        }
        if (in_array($controllerClass, $this->getControllers()['exclude'])) {
            return false;
        }
        return true;
    }

}

