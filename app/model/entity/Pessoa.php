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

    /**
    * @Colmap = ide_sexo
    * @Persistence (type=inteiro,NotNull=true)
    * @Relationship (objeto=Sexo,type=OneToOne)
    */
    private $sexo;

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

    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

}