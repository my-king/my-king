<?php

/**
* @Table = pessoa
* @Schema = myking
*/
class Pessoa {

    /**
    * @Serial
    * @Colmap = id_pessoa
    */
    private $id;

    /**
    * @Colmap = nome
    * @Persistence (type=texto,NotNull=true,MaxSize=45)
    */
    private $nome;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

}