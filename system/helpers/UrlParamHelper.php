<?php

/**
 * 
 *    Este arquivo é parte do Framework MyKing
 *    
 * 
 *    MyKing é um software livre; você pode redistribui-lo e/ou 
 *    
 *    modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 *    
 *    publicada pela Fundação do Software Livre (FSF); na versão 2 da 
 *    
 *    Licença.
 *    
 *    
 *    Este programa é distribuido na esperança que possa ser  util, 
 *    
 *    mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 *    
 *    MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 *    
 *    Licença Pública Geral GNU para maiores detalhes.
 *    
 *    
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    
 *    junto com este programa, se não, escreva para a Fundação do Software
 *    
 *    Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Capitura e tratamento da url
 * @author Igor da Hora <igordahora@gmail.com>
 */
class UrlParamHelper {

    private $url;
    private $explode;
    private $controller;
    private $action;
    private $params;

    public function __construct() {
        $this->setUrl();
        $this->setExplode();
        $this->setController();
        $this->setAction();
        $this->setParams();
    }

    private function setUrl() {
        $_SERVER['QUERY_STRING'] = (!empty($_SERVER['QUERY_STRING']) ) ? $_SERVER['QUERY_STRING'] : 'Index/index';
        $this->url = $_SERVER['QUERY_STRING'];
    }

    private function setExplode() {

        $ec = strpos($this->url, "&");
        $igual = strpos($this->url, "=");

        if ($ec === false && $igual === false) {
            $this->explode = explode('/', $this->url);
        } else {

            $tExplode = explode('&', $this->url);
            $explode = array();

            foreach ($tExplode as $value) {
                $explode[] = str_replace("=", "/", $value);
            }

            $tUrl = implode("/", $explode);

            $url = explode('/', $tUrl);
            unset($url[0], $url[2]);

            $urlTratada = array();

            foreach ($url as $value) {
                $urlTratada[] = $value;
            }

            $this->explode = $urlTratada;
        }
    }

    public function getController() {
        return $this->controller;
    }

    private function setController() {
        $this->controller = $this->explode[0];
    }

    public function getAction() {
        return $this->action;
    }

    private function setAction() {
        $ac = (!isset($this->explode[1]) || $this->explode[1] == null ? 'index' : $this->explode[1]);
        $this->action = $ac;
    }

    private function setParams() {

        unset($this->explode[0], $this->explode[1]);

        if (end($this->explode) == null) {
            array_pop($this->explode);
        }

        $i = 0;
        if (!empty($this->explode)) {

            foreach ($this->explode as $val) {
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
            $this->params = array_combine($ind, $value);
        } else {
            $this->params = array();
        }
    }

    /**
     * Retorna o parametro buscado
     * @param string $name
     * @return array $this->params[$name] | array $this->params
     */
    public function getParam($name = null) {
        if ($name !== null) {
            return $this->params[$name];
        } else {
            return $this->params;
        }
    }

    /**
     * Verifica se o parametro existe
     * @param string $name
     * @return boolean
     */
    public function isParam($name) {
        if (isset($this->params[$name])) {
            return true;
        } else {
            return false;
        }
    }

}
