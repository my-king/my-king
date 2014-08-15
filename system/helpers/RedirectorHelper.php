<?php

/**
 * Class de redirecionamento de urls
 * @author igor da hora santos <igordahora@gmail.com>
 * @package HELPERS
 */
class RedirectorHelper {

    public static $parameters = array();

    /**
     * Redirecionar para uma pagina dentro do projeto
     * @param string $data
     */
    public static function go($data) {
        RedirectorHelper::$parameters = array();
        header("Location: " . PATH_URL . "index.php?" . $data);
    }

    /**
     * Adiciona parametros na url
     * @param string $name
     * @param string $value
     */
    public static function addUrlParameter($name, $value) {
        RedirectorHelper::$parameters[$name] = $value;
    }

    /**
     * Obter valores dos parametros setado dentro de uma url
     * @return array $parms
     */
    public static function getUrlParameters() {
        $parms = null;
        if (count(RedirectorHelper::$parameters) > 0) {
            foreach (RedirectorHelper::$parameters as $name => $value) {
                $parms.= $name . '/' . $value . '/';
            }
            $parms = substr($parms, 0, -1);
        }
        return $parms;
    }

    /**
     * Redirecionar para um controller
     * @param string $controller
     */
    public static function goToController($controller) {
        $parameters = ( RedirectorHelper::getUrlParameters() !== null ) ? '/' . RedirectorHelper::getUrlParameters() : '';
        RedirectorHelper::go($controller . '/index' . $parameters);
    }

    /**
     * Redirecionar para um action do controle em uso
     * @param string $action Nome da action
     */
    public static function goToAction($action) {
        $parameters = ( RedirectorHelper::getUrlParameters() !== null ) ? "/" . RedirectorHelper::getUrlParameters() : "";
        RedirectorHelper::go(CurrentSystemHelper::getCurrentController() . '/' . $action . $parameters);
    }

    /**
     * Redirecionar para um controle e uma action
     * @param string $controller Nome do controller
     * @param string $action Nome da action
     */
    public static function goToControllerAction($controller, $action) {
        $parameters = ( RedirectorHelper::getUrlParameters() != "" ) ? "/" . RedirectorHelper::getUrlParameters() : "";
        RedirectorHelper::go($controller . '/' . $action . $parameters);
    }

    /**
     * Redirecionar para a index
     */
    public static function goToIndex() {
        RedirectorHelper::$parameters = array();
        RedirectorHelper::go('Principal/index');
    }

    /**
     * Redirecionar para uma url qualquer
     */
    public static function goToUrl($url) {
        RedirectorHelper::$parameters = array();
        header("Location: " . $url);
    }

}