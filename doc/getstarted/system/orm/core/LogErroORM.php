<?php

/**
 * Classe de log
 * @author igorsantos
 */
class LogErroORM {

    public static function gerarLog($acao, $errorMsg) {

        $log = "------------------------------------------ LOG (" . date("d-m-Y H:i:s") . ") C/A: {$_SERVER['QUERY_STRING']} ------------------------------------------\n";
        $log .= "#ERROR#: [{$errorMsg}]\n";
        $log .= "#AÇÃO#: [{$acao}]\n";
        $log .= "--------------------------------------------------------------------- FIM LOG ---------------------------------------------------------------------\n\n";

        $fopen = fopen(PATH_LOG_ORM, 'a');
        fwrite($fopen, $log);
        fclose($fopen);
    }

    public static function gerarLogDelete($errorMsg, $query, array $dados = null) {
        
        $log = "------------------------------------------ LOG (" . date("d-m-Y H:i:s") . ") C/A: {$_SERVER['QUERY_STRING']} ------------------------------------------\n";
        $log .= "#ERROR#: [{$errorMsg}]\n";
        $log .= "#SQL::DELETE#: [{$query}]\n";

        if ($dados !== null) {

            $campos = array();
            
            foreach ($dados as $indice => $value) {
                if(is_array($value)){
                    foreach ($value as $id) {
                        $campos[] = "':{$indice}' => '{$id}'";
                    }
                }else{
                    $campos[] = "':{$indice}' => '{$value}'";
                }
            }
            
            $campos = implode(",", $campos);
            $log .= "#PARAMETROS#: [{$campos}]\n";
        }

        $log .= "--------------------------------------------------------------------- FIM LOG ---------------------------------------------------------------------\n\n";

        $fopen = fopen(PATH_LOG_ORM, 'a');
        fwrite($fopen, $log);
        fclose($fopen);
        
    }

    public static function gerarLogSelect($errorMsg, $query, $dados = null) {

        $log = "------------------------------------------ LOG (" . date("d-m-Y H:i:s") . ") C/A: {$_SERVER['QUERY_STRING']} ------------------------------------------\n";
        $log .= "#ERROR#: [{$errorMsg}]\n";
        $log .= "#SQL::SELECT#: [{$query}]\n";

        if ($dados !== null) {

            $campos = array();
            foreach ($dados as $indice => $value) {
                $campos[] = "':{$indice}' => '{$value}'";
            }
            $campos = implode(",", $campos);
            $log .= "#PARAMETROS#: [{$campos}]\n";
        }

        $log .= "--------------------------------------------------------------------- FIM LOG ---------------------------------------------------------------------\n\n";

        $fopen = fopen(PATH_LOG_ORM, 'a');
        fwrite($fopen, $log);
        fclose($fopen);
        
    }

    public static function gerarLogInsert($errorMsg, $query, $dados, $collection = null) {

        $log = "------------------------------------------ LOG (" . date("d-m-Y H:i:s") . ") C/A: {$_SERVER['QUERY_STRING']} ------------------------------------------\n";
        $log .= "#ERROR#: [{$errorMsg}]\n";
        $log .= "#SQL::INSERT#: [{$query}]\n";

        $campos = array();
        foreach ($dados as $indice => $value) {
            $campos[] = "':{$indice}' => '{$value}'";
        }
        $campos = implode(",", $campos);

        $log .= "#PARAMETROS#: [{$campos}]\n";

        if ($collection !== null) {
            foreach ($collection as $atributo => $array) {
                foreach ($array as $indice => $idRelationship) {
                    if ($indice != "@query") {
                        foreach ($idRelationship as $value) {
                            $log .= "#SQL::INSERT::COLLECTION::{$atributo}#: [" . $array['@query'] . "]\n";
                            $log .= "#PARAMETRO::COLLECTION::{$atributo}#: [':{$indice}' => '{$value}']\n";
                        }
                    }
                }
            }
        }

        $log .= "--------------------------------------------------------------------- FIM LOG ---------------------------------------------------------------------\n\n";

        $fopen = fopen(PATH_LOG_ORM, 'a');
        fwrite($fopen, $log);
        fclose($fopen);
    }

    public static function gerarLogUpdate($errorMsg, $update = null, $collection = null, $id_ref = null) {

        $log = "------------------------------------------ LOG (" . date("d-m-Y H:i:s") . ") C/A: {$_SERVER['QUERY_STRING']} ------------------------------------------\n";
        
        $log .= "#ERROR#: [{$errorMsg}]\n";

        // Preparar query da entity
        if ($update !== null) {
            $log .= "#SQL::UPDATE#: [" . $update['query'] . "]\n";

            $campos = array();
            foreach ($update['colmap'] as $indice => $value) {
                $campos[] = "':{$indice}' => '{$value}'";
            }

            $campos = implode(",", $campos);

            $log .= "#PARAMETROS#: [{$campos}]\n";
        }


        if ($collection !== null) {
            $id_colmap = $id_ref['id_colmap'];
            $id_entity = $id_ref['id_entity'];
            $chaves = array();
            foreach ($collection as $key => $value) {
                $chaves[] = $key;
            }

            foreach ($chaves as $atrib) {

                # Deletar collection
                $log .= "#SQL::DELETE::COLLECTION::{$atrib}#: [" . $collection[$atrib]['@query']['delete'] . "]\n";

                foreach ($collection[$atrib] as $atributo => $array) {

                    if ($atributo != "@query") {
                        if (count($array) > 0) {
                            $log .= "#SQL::INSERT::COLLECTION::{$atrib}#: [" . $collection[$atrib]['@query']['insert'] . "]\n";
                            foreach ($array as $idRelationship) {
                                $log .= "#PARAMETRO::COLLECTION::{$atrib}#: [':{$id_colmap}' => '{$id_entity}',':{$atributo}' => '{$idRelationship}']\n";
                            }
                        }
                    }
                }
            }
        }

        $log .= "--------------------------------------------------------------------- FIM LOG ---------------------------------------------------------------------\n\n";

        $fopen = fopen(PATH_LOG_ORM, 'a');
        fwrite($fopen, $log);
        fclose($fopen);
    }

}

?>
