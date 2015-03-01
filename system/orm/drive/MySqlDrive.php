<?php

class MySqlDrive {

    private $classConn;

    public function __construct(&$classConn) {
        $this->classConn = $classConn;
    }

    public function getConn() {
        $dados = $this->classConn;
        try {
            
            $conn = new PDO(
                        "{$dados->type}:host={$dados->server};dbname={$dados->database};", //servidor e Banco de Dados 
                        "{$dados->user}", //usuario
                        "{$dados->password}", // Senha
                        array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1", //For�a UTF8
                            PDO::ATTR_PERSISTENT => true // Persistir a conexao
                        )
                    );

            return $conn;
        } catch (Exception $e) {
            LogErroORM::gerarLog("CONEXAO - N�O FOI POSSIVEL ESTABELECER UMA CONEX�O COM O SERVIDOR", $e->getMessage());
            return false;
        }
    }

}
