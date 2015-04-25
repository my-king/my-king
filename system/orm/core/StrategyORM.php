<?php

class StrategyORM {

    public static function getStrategy(&$class) {
        
        $reflection = new ReflectionORM($class);
        $registry = RegistryORM::getInstancia();
        $Dal = ($reflection->getClassAnnotations('@Dal') === '') ? 'DefaultDAL' : $reflection->getClassAnnotations('@Dal');
        $classConn = $registry->getClassConn($Dal);
        
        $type = array("mysql" => "MySql", "pgsql" => "PgSql");
        if (isset($type[$classConn->type])) {
            $strategy = $type[$classConn->type] . ucfirst($classConn->lib) . "Strategy";
            $registry->set($Dal);
            $conexao = $registry->get($Dal); 
            unset($classConn,$Dal);
            return new $strategy($conexao, $reflection);
        } else {
            LogErroORM::gerarLog('Tentativa de Conexão', 'O tipo da conexão [' . $classConn->type . '] informada não existe, verifique o arquivo de configuração de banco de dados');
            unset($classConn,$Dal);
            return false;
        }
        
    }

}