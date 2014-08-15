<?php

abstract class PdoFactoryORM extends AbstractFactoryORM {

    public $conn = null;

    public function __construct() {
        $dados = $this->getDadosConexao();
        parent::__construct($dados);
        $this->factory = "Pdo";
    }

    public function connectDB() {

        try {

            if ($this->type === 'pgsql') {

                $this->conn = new PDO("{$this->type}:host={$this->server};dbname={$this->database};", //servidor e Banco de Dados 
                                "{$this->user}", //usuario
                                "{$this->password}",
                                array(PDO::ATTR_PERSISTENT => true)
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->exec("SET NAMES 'iso-8859-1'");

                return $this->conn;
                
            } else if ($this->type === 'mysql') {

                $this->conn = new PDO("{$this->type}:host={$this->server};dbname={$this->database};", //servidor e Banco de Dados 
                                "{$this->user}", //usuario
                                "{$this->password}", // Senha
                                array(
                                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1", //For�a UTF8
                                    PDO::ATTR_PERSISTENT => true // Persistir a conexao
                                )
                );

                return $this->conn;
                
            } else {
                LogErroORM::gerarLog("CONEXAO - N�O FOI POSSIVEL CONNECTAR O 'type' ESPECIFICADO NA CONFIGURA��O N�O EXISTE OU � NULO", $e->getMessage());
                RedirectorHelper::goToControllerAction("Errors", "database");
            }
            
        } catch (Exception $e) {
            LogErroORM::gerarLog("CONEXAO - N�O FOI POSSIVEL ESTABELECER UMA CONEX�O COM O SERVIDOR", $e->getMessage());
            RedirectorHelper::goToControllerAction("Errors", "database");
        }
    }

}