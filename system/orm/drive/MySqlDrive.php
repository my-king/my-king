<?php

class MySqlDrive {

    private $classConn;

    public function __construct(&$classConn) {
        $this->classConn = $classConn;
    }

    public function getConn() {
        $dados = $this->classConn;
        $port = ($dados->port !== null) ? "port={$dados->port};":'';
        try {
            $conn = new PDO(
                        "{$dados->type}:host={$dados->server};{$port}dbname={$dados->database};", //servidor e Banco de Dados 
                        "{$dados->user}", //usuario
                        "{$dados->password}", // Senha
                        array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1", //Força UTF8
                            PDO::ATTR_PERSISTENT => true // Persistir a conexao
                        )
                    );

            return $conn;
        } catch (Exception $e) {
            LogErroORM::gerarLog("CONEXÃO - NÃO FOI POSSIVEL ESTABELECER UMA CONEXÃO COM O SERVIDOR", $e->getMessage());
            return false;
        }
    }

}
