<?php

abstract class AbstractFactoryORM {

    protected $type;
    protected $server;
    protected $port;
    protected $user;
    protected $password;
    protected $database;
    protected $factory;
    protected $nameDAL;

    protected function __construct($dados,$nameDAL) {
        $this->nameDAL = $nameDAL;
        $this->type = $dados['type'];
        $this->server = $dados['server'];
        $this->port = (isset($dados['port'])) ? $dados['port'] : 5432;
        $this->database = $dados['database'];
        $this->user = $dados['user'];
        $this->password = $dados['password'];
    }

    abstract public function getNameDAL();
    
    abstract public function getDadosConexao();
    
    public function getType() {
        return $this->type;
    }
    
    public function getFactory(){
        return $this->factory;
    }
    
}