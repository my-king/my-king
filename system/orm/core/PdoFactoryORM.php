<?php

/**
 * @author igor
 */
abstract class PdoFactoryORM extends AbstractFactoryORM {

    public $conn = null;

    public function __construct() {
        parent::__construct($this->getDadosConexao(), $this->getNameDAL());
        $this->factory = "Pdo";
    }

    public function connectDB() {
        
        /* Inicia a instancia de resgistro de banco de dados */
        $registry = RegistryORM::getInstancia();

        /* Criar objeto com dados para conexão */
        $classConn = new stdClass();
        $classConn->type = $this->type;
        $classConn->server = $this->server;
        $classConn->port = $this->port;
        $classConn->database = $this->database;
        $classConn->user = $this->user;
        $classConn->password = $this->password;
        
        $registry->set($this->nameDAL,$classConn);
        unset($classConn);
        
        return $registry->get($this->nameDAL);
        
    }

}