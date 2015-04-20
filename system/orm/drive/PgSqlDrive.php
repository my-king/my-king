<?php

class PgSqlDrive {

    private $classConn;

    public function __construct(&$classConn) {
        $this->classConn = $classConn;
    }

    public function getConn() {
        $dados = $this->classConn;
        $port = ($dados->port !== null) ? "port={$dados->port};" : '';
        try {
            $conn = new PDO("{$dados->type}:host={$dados->server};{$port}dbname={$dados->database};", "{$dados->user}", "{$dados->password}", array(PDO::ATTR_PERSISTENT => true)
            );
            isset($port);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec("SET NAMES 'iso-8859-1'");

            return $conn;
        } catch (Exception $e) {
            LogErroORM::gerarLog("CONEXAO - NÃO FOI POSSIVEL ESTABELECER UMA CONEX�O COM O SERVIDOR", $e->getMessage());
            return false;
        }
    }

}
