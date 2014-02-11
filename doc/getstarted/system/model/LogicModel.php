<?php

abstract class LogicModel {

    protected $DAO;

    public function __construct(DaoModel $objDao) {
        $this->DAO = $objDao;
    }    
    
    public function obter($where, $objectCollection = null) {
        return $this->DAO->obter($where, $objectCollection);
    }
    
    public function obterPorId($id, $objectCollection = null, $exception = null) {
        return $this->DAO->obterPorId($id, $objectCollection, $exception);
    }

    public function listar($where = null, $orderby = null, $objectCollection = null, $exception = null, $offset = null) {
        return $this->DAO->listar($where, $orderby, $objectCollection, $exception, $offset);
    }

    public function salvar($dados, $objectResult = null, $exception = null) {
        return $this->DAO->salvar($dados, $objectResult, $exception);
    }

    public function excluirPorId($id) {
        return $this->DAO->excluirPorId($id);
    }

    public function excluir($where, $dados = null) {
        return $this->DAO->excluir($where, $dados);
    }    
    
    public function select($query, array $dados = null) {
        return $this->DAO->select( $query , $dados );
    }

    public function selectAll($query, array $dados = null) {
        return $this->DAO->selectAll( $query , $dados );
    }
    
    public function loadObject($array, $objectCollection = null, $exception = null) {
        return $this->DAO->loadObject($array, $objectCollection, $exception);
    }
    
    public function objectToArray($object) {
        return $this->DAO->objectToArray($object);
    }    

    public function objectToJson($object, array $arrayAdd = null) {
        return $this->DAO->objectToJson($object, $arrayAdd);
    }
    
    public function selectObjectAll($colunas = null, $where = null, $orderby = null, array $dados = null) {
        return $this->DAO->selectObjectAll($colunas, $where, $orderby, $dados);
    }
    
    public function totalRegistro($where = null) {
        return $this->DAO->totalRegistro($where);
    }
    
    public function somar($atributo, $where = null) {
        return $this->DAO->somar($atributo, $where);
    }
}

?>
