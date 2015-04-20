<?php

class StrategyORM {

    public static function getStrategy(&$class) {
        $reflection = new ReflectionORM($class);
        $Dal = ($reflection->getClassAnnotations('@Dal') === '') ? 'DefaultDAL' : $reflection->getClassAnnotations('@Dal');
        $DAL = $Dal::getInstancia();
        $type = array("mysql" => "MySql", "pgsql" => "PgSql");
        if(isset($type[$DAL->getType()])){
            $strategy = $type[$DAL->getType()] . $DAL->getFactory() . "Strategy";
            $conexao = $DAL->connectDB();
            return new $strategy($conexao, $reflection );            
        }else{
            LogErroORM::gerarLog('Tentativa de Conexão', 'O tipo da conexão ['.$DAL->getType().'] informada não existe, verifique o arquivo de configuração de banco de dados');
            return false;
        }
    }

}
