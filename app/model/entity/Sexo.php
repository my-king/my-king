<?php

/**
* @Dal = conexao2
* @Schema = public
* @Table = sexo
*/
class Sexo {

    /**
    * @Serial
    * @Colmap = ide_sexo
    */
    private $id;

    /**
    * @Colmap = nome
    * @Persistence (type=texto,NotNull=true,MinSize=10)
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