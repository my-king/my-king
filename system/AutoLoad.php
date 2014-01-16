<?php

function __autoload($class) {

    $dir_template = null;
    if (defined('TEMPLATE')) {
        $dir_template = PATH . "system/templates/helpers/" . TEMPLATE . "/";
    }
    
    if (defined('TEMPLATE') === true && file_exists($dir_template.$class.'.php') === true && preg_match('/^T[^a-z]*$/', $class{0}) && preg_match('/^[^a-z]*$/', $class{1}) && preg_match("/Helper$/", $class)) {
        $dir = PATH . "system/templates/helpers/" . TEMPLATE . "/";
    } elseif (preg_match('/^T[^a-z]*$/', $class{0}) && preg_match('/^[^a-z]*$/', $class{1}) && preg_match("/Helper$/", $class)) {
        $dir = PATH . "system/templates/helpers/";
    } elseif (preg_match('/^T[^a-z]*$/', $class{0}) && preg_match('/^[^a-z]*$/', $class{1})) {
        $dir = PATH . "system/templates/";
    } elseif (preg_match("/Helper$/", $class)) {
        $dir = PATH . "system/helpers/";
    } elseif (preg_match('/^V[^a-z]*$/', $class{0}) && preg_match('/^[^a-z]*$/', $class{1}) && preg_match("/Model$/", $class)) {
        $dir = PATH . "system/model/";
    } elseif (preg_match("/Model$/", $class)) {
        $dir = PATH . "system/model/";
    } elseif (preg_match("/ORM$/", $class)) {
        $dir = PATH . "system/orm/core/";
    } elseif (preg_match("/DAL$/", $class)) {
        $dir = PATH . "system/orm/dal/";
    } elseif (preg_match("/Strategy$/", $class)) {
        $dir = PATH . "system/orm/strategy/";
    } elseif (preg_match('/^V[^a-z]*$/', $class{0}) && preg_match('/^[^a-z]*$/', $class{1}) && preg_match("/Logic$/", $class)) {
        $dir = PATH . "app/model/logic/views/";
    } elseif (preg_match("/Logic$/", $class)) {
        $dir = PATH . "app/model/logic/";
    } elseif (preg_match('/^V[^a-z]*$/', $class{0}) && preg_match('/^[^a-z]*$/', $class{1}) && preg_match("/DAO$/", $class)) {
        $dir = PATH . "app/model/dao/views/";
    } elseif (preg_match("/DAO$/", $class)) {
        $dir = PATH . "app/model/dao/";
    } elseif (preg_match('/^V[^a-z]*$/', $class{0}) && preg_match('/^[^a-z]*$/', $class{1})) {
        $dir = PATH . "app/model/entity/views/";
    } else {
        $dir = PATH . "app/model/entity/";
    }

    $path_class = $dir . $class . ".php";
    if (file_exists($path_class)) {
        require_once $path_class;
    }
}

?>
