<?php

/**
 * @author Igor da Hora <igordahora@gmail.com>
 * @package system
 * @subpackage helpers
 */
class CurrentUrlHelper {

    public static function getController() {
        global $start;
        return $start->getController();
    }

    public static function getAction() {
        global $start;
        return $start->getAction();
    }

    public static function getParam($name = null) {
        global $start;
        return $start->getParam($name);
    }

    public static function isParam($name) {
        global $start;
        return $start->isParam($name);
    }

}
