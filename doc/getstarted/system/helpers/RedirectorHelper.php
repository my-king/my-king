<?php

/**
 * Description of RedirectorHelper
 *
 * @author igorsantos
 * @package HELPERS
 */
class RedirectorHelper {

    protected $parameters = array();

    #metodo padr?o para redirecionamento de url 

    protected function go($data) {
        header("Location: " . PATH_URL . "index.php?" . $data);
    }

    #setar os parametos da url

    public function setUrlParameter($name, $value) {
        $this->parameters[$name] = $value;
        return $this;
    }

    #recuperar os parametos da url 

    public function getUrlParameters() {
        
        $parms = "";
        foreach ($this->parameters as $name => $value) {
            $parms.= $name . '/' . $value . '/';
        }
        $parms = substr($parms, 0, -1);
        return $parms;
    }

    #redirecionar para um controller

    public function goToController($controller) {
        $parameters = ( $this->getUrlParameters() != "" ) ? "/" . $this->getUrlParameters() : "";
        $this->go($controller . '/index' . $parameters);
    }

    #pegar o controller que estar sendo usado

    protected function getCurrentController() {
        global $start;
        return $start->_controller;
    }

    #pegar o action que estar sendo usado

    protected function getCurrentAction() {
        global $start;
        return $start->_action;
    }

    #redirecionar para um action do controle em uso

    public function goToAction($action) {
        $parameters = ( $this->getUrlParameters() != "" ) ? "/" . $this->getUrlParameters() : "";
        $this->go($this->getCurrentController() . '/' . $action . $parameters);
    }

    #redirecionar para um controle e uma action

    public function goToControllerAction($controller, $action) {
        $parameters = ( $this->getUrlParameters() != "" ) ? "/" . $this->getUrlParameters() : "";
        $this->go($controller . '/' . $action . $parameters);
    }

    #redirecionar para index

    public function goToIndex() {
        $this->go('Principal/index');
    }

    #redirecionar para uma url

    public function goToUrl($url) {
        header("Location: " . $url);
    }

}

?>
