<?php

abstract class AbstractFactoryORM {

    protected $type;
    protected $server;
    protected $user;
    protected $password;
    protected $database;
    protected $factory;


    protected function __construct($dados) {     
        $this->type = $dados['type'];
        $this->server = $dados['server'];
        $this->database = $dados['database'];
        $this->user = $dados['user'];
        $this->password = $dados['password'];     
    }

    abstract public function getDadosConexao();
    
    public function getType() {
        return $this->type;
    }
    
    public function getFactory(){
        return $this->factory;
    }
    
}

?>
