<?php

/**
 * Description of AjaxHelper
 * @author igor
 */
class TAjaxHelper {

    public static function mountArrayObjectToJson($arrayObject, $nome = 'nome', $value = 'id') {

        $array = false;
        
        # Criar array dos itens a serem retornados
        if ($arrayObject != null) {

            $getNome = 'get' . ucfirst($nome);
            $getValue = "get" . ucfirst($value);

            foreach ($arrayObject as $objeto) {
                $array[] = array('value' => $objeto->$getValue(), 'nome' => utf8_encode($objeto->$getNome()));
            }
            
        }        
        unset($arrayObject);

        # Retornar json
        return json_encode($array);
    }

}
