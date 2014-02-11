<?php

class System {

    private $_url;
    private $_explode;
    public $_controller;
    public $_action;
    public $_params;

    public function __construct() {
        # Informa qual o conjunto de caracteres será usado.
        header('Content-Type: text/html; charset=utf-8'); # Padrão utf-8
        $this->setUrl();
        $this->setExplode();
        $this->setController();
        $this->setAction();
        $this->setParams();
    }

    private function setUrl() {        
        $_SERVER['QUERY_STRING'] = (!empty($_SERVER['QUERY_STRING']) ) ? $_SERVER['QUERY_STRING'] : 'Index/index';
        $this->_url = $_SERVER['QUERY_STRING'];
    }

    private function setExplode() {
        
        $ec = strpos($this->_url, "&");
        $igual = strpos($this->_url, "=");

        if($ec === false && $igual === false){
            $this->_explode = explode('/', $this->_url);
        }else{
            
            $tExplode = explode('&', $this->_url);
            $explode = array();
            
            foreach ($tExplode as $value) {
                $explode[] = str_replace("=", "/", $value);
            }
            
            $tUrl = implode("/", $explode);
            
            $url = explode('/', $tUrl);
            unset($url[0],$url[2]);
            
            $urlTratada = array();
            
            foreach ($url as $value) {
                $urlTratada[] = $value;
            }
            
            $this->_explode = $urlTratada;
        }
    }

    private function setController() {
        $this->_controller = $this->_explode[0];
    }

    private function setAction() {
        $ac = (!isset($this->_explode[1]) || $this->_explode[1] == null ? 'index' : $this->_explode[1]);
        $this->_action = $ac;
    }

    private function setParams() {

        unset($this->_explode[0], $this->_explode[1]);

        if (end($this->_explode) == null) {
            array_pop($this->_explode);
        }

        $i = 0;
        if (!empty($this->_explode)) {

            foreach ($this->_explode as $val) {
                if ($i % 2 == 0) {
                    $ind[] = $val;
                } else {
                    $value[] = $val;
                }
                $i++;
            }
        } else {
            $ind = array();
            $value = array();
        }

        if (count($ind) == count($value) && !empty($ind) && !empty($value)) {
            $this->_params = array_combine($ind, $value);
        } else {
            $this->_params = array();
        }

    }

    public function getParam($name = null) {
        if ($name != null) {
            return $this->_params[$name];
        } else {
            return $this->_params;
        }
    }

    # verificar se existe parametro
    public function isParam($name) {

        if (isset($this->_params[$name])) {
            return true;
        } else {
            return false;
        }
    }

    public function run() {

        $controller = $this->_controller . 'Controller';
        $controller_path = CONTROLLERS . $controller .'.php';

        if (!file_exists($controller_path))
            header ("Location: index.php?Errors/HTTP_404");

        require_once ($controller_path);
        $app = new $controller();

        if (!method_exists($app, $this->_action))
            header ("Location: index.php?Errors/HTTP_404");

        $method = new ReflectionMethod($controller, $this->_action);
        
        if (!$method->isPublic())
            header ("Location: index.php?Errors/HTTP_404");
        
        $action = $this->_action;
        $app->init();
        $app->$action();
    }

}

?>
