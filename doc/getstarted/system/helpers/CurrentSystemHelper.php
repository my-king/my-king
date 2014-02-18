<?php

/**
 * Description of RedirectorHelper
 *
 * @author igorsantos
 * @package HELPERS
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

}

?>
