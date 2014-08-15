<?php

class TPostHelper {

    public static function queryString($url) {
        $arrayUrl = explode('?', $url);
        $queryString = $arrayUrl[1];
        unset($arrayUrl);
        return $queryString;
    }

    public static function isParam(Array &$params, $search){
        
        if(isset($params[$search])){
            return true;
        }
        
        return false;
    }
    
    public static function getParam(Array &$params, $search){
        
        if(isset($params[$search])){
            return $params[$search];
        }
        
        return null;
    }

    public static function mountParams($url) {
        if ($url !== '') {

            $queryString = TPostHelper::queryString($url);
            $arrayQueryString = explode('/', $queryString);

            unset($arrayQueryString[0], $arrayQueryString[1]);
            if (end($arrayQueryString) == null) {
                array_pop($arrayQueryString);
            }

            $i = 0;
            if (!empty($arrayQueryString)) {

                foreach ($arrayQueryString as $val) {
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

            $params = null;
            if (count($ind) == count($value) && !empty($ind) && !empty($value)) {
                $params = array_combine($ind, $value);
            }
            return $params;
        } else {
            return null;
        }
    }

    public static function getFuncionalidadeFromUrl($url) {
        if ($url !== '') {
            $queryString = TPostHelper::queryString($url);
            $arrayQueryString = explode('/', $queryString);
            $objFuncionalidade = new stdClass();
            $objFuncionalidade->modulo = $arrayQueryString[0];
            $objFuncionalidade->page = $arrayQueryString[1];
            return $objFuncionalidade;
        } else {
            return false;
        }
    }

}
