<?php

interface IStrategyOrm {
    public function __construct(&$conn,$reflection);
    public function loadObjectPersistInsert($array);
    public function loadObjectPersistUpdate($array);
    public function loadObject($array, $lazy = false);
    public function execut($query);
    public function select($query);
    public function selectAll($query);
    public function obterPorId($id);
    public function listar();
    public function salvar($dados);
    public function inserir($dados);
    public function atualizar($dados);
    public function delete($id);
}

?>
