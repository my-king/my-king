<?php

class DefaultDAL extends PdoFactoryORM{

    private static $instancia = null;

    public static function getInstancia() {
        if (self::$instancia == null) {
            self::$instancia = new DefaultDAL();
        }
        return self::$instancia;
    }

    public function __clone() {
        trigger_error('Clone não permitido.', E_USER_ERROR);
    }
    
    
    public function getDadosConexao() {
        $ini = parse_ini_file('system/config/config.ini', true);
        return $ini['DefaultDAL'];            
    }    
    
}

?>
