<?php

class PgSqlDrive {

    private $classConn;

    public function __construct(&$classConn) {
        $this->classConn = $classConn;
    }

    public function getConn() {
        $dados = $this->classConn;
        try {
            $conn = new PDO("{$dados->type}:host={$dados->server};port={$dados->port};dbname={$dados->database};", //servidor e Banco de Dados 
                    "{$dados->user}", //usuario
                    "{$dados->password}", array(PDO::ATTR_PERSISTENT => true)
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec("SET NAMES 'iso-8859-1'");

            return $conn;
        } catch (Exception $e) {
            LogErroORM::gerarLog("CONEXAO - N�O FOI POSSIVEL ESTABELECER UMA CONEX�O COM O SERVIDOR", $e->getMessage());
            return false;
        }
    }

}
