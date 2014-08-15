<?php

/**
 * @author Igor da Hora <igordahora@gmail.com>
 * @package system
 * @subpackage helpers
 */
class CurrentSystemHelper {

    public static function getCurrentController() {
        global $start;
        return $start->_controller;
    }

    public static function getCurrentAction() {
        global $start;
        return $start->_action;
    }
    
    /**
     * Retornar param se existir
     */
   public static function getCurrentParam($param) {
        global $start;
        $params = $start->_params;
        if ( isset($params[$param]) ) {
            $param = $params[$param];
        }
        return $param;
    }


}

?>
