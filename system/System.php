<?php

class System {

    private $objUrlParam;

    public function __construct() {
        # Informa qual o conjunto de caracteres vai ser utilizado
        header('Content-Type: text/html; charset=UTF-8');
        $this->objUrlParam = new UrlParamHelper();
    }
    
    public function getController() {
        return $this->objUrlParam->getController();
    }
    
    public function getAction() {
        return $this->objUrlParam->getAction();
    }
    
    public function getParam($name = null) {
        return $this->objUrlParam->getParam($name);
    }

    public function isParam($name) {
        return $this->objUrlParam->isParam($name);
    }
    
    public function run() {

        $controller = $this->objUrlParam->getController() . 'Controller';
        $controller_path = CONTROLLERS . $controller .'.php';

        if (!file_exists($controller_path))
            header ("Location: index.php?Errors/HTTP_404");

        require_once ($controller_path);
        $app = new $controller();

        if (!method_exists($app, $this->objUrlParam->getAction()))
            header ("Location: index.php?Errors/HTTP_404");

        $method = new ReflectionMethod($controller, $this->objUrlParam->getAction());
        
        if (!$method->isPublic())
            header ("Location: index.php?Errors/HTTP_404");
        
        $action = $this->objUrlParam->getAction();
        $app->init();
        $app->$action();
    }

}