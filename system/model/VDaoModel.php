<?php

/**
 * Class de responsavel pela regra de negocio de persistencia de dados
 *
 * @author igor
 */
abstract class VDaoModel {

    /**
     * Strategy a ser adotada
     * @var type 
     */
    private $strategy;

    function __construct($class) {
        $this->strategy = StrategyORM::getStrategy($class);
        if (!$this->strategy) {
            RedirectorHelper::goToControllerAction('Errors', 'database');
        }
    }

    public function obter($where, $objectCollection = null, $exception = null) {

        #pegar objeto pelo id
        $objeto = $this->strategy->obter($where, $objectCollection, $exception);

        # Se o retorno for false
        if (!$objeto) {
            return false;
        }

        # retorno em formato de objeto
        return $objeto;
    }

    public function obterPorId($id, $objectCollection = null, $exception = null) {

        #pegar objeto pelo id
        $objeto = $this->strategy->obterPorId($id, $objectCollection, $exception);

        # Se o retorno for false
        if (!$objeto) {
            return false;
        }

        # retorno em formato de objeto
        return $objeto;
    }

    public function listar($where = null, $orderby = null, $objectCollection = null, $exception = null, $offset = null, $limit = null) {

        #pegar cole��o de objetos
        $collection = $this->strategy->listar($where, $orderby, $objectCollection, $exception, $offset, $limit);

        # Se o retorno for false
        if (!$collection) {
            return false;
        }

        # retorna uma cole��o de objetos
        return $collection;
    }

    public function select($query, array $dados = null) {
        return $this->strategy->select($query, $dados);
    }

    public function selectAll($query, array $dados = null) {
        return $this->strategy->selectAll($query, $dados);
    }

    public function selectObjectAll($colunas = null, $where = null, $orderby = null, array $dados = null) {
        return $this->strategy->selectObjectAll($colunas, $where, $orderby, $dados);
    }

    public function loadObject($array, $objectCollection = null, $exception = null) {
        return $this->strategy->loadObject($array, $objectCollection, $exception);
    }

    public function objectToArray($object) {
        return $this->strategy->objectToArray($object);
    }

    public function objectToJson($object, array $arrayAdd = null) {
        return $this->strategy->objectToJson($object, $arrayAdd);
    }

    public function totalRegistro($where = null) {
        return $this->strategy->totalRegistro($where);
    }

    public function somar($atributo, $where = null) {
        return $this->strategy->somar($atributo, $where);
    }

    public function maiorValor($atributo, $where = null) {
        return $this->strategy->max($atributo, $where);
    }

    public function menorValor($atributo, $where = null) {
        return $this->strategy->min($atributo, $where);
    }

}
