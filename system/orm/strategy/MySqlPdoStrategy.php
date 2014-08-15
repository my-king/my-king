<?php

class MySqlPdoStrategy {

    private $conn;
    private $reflection;
    private $tabela;
    private $colunas;
    private $propAtributos;

    public function __construct(&$conn, $reflection) {
        $this->conn = $conn;
        $this->reflection = $reflection;
        $this->tabela = $this->reflection->getClassAnnotations('@Table');
        $this->colunas = implode($this->reflection->getColmap(), ',');
        $this->propAtributos = $this->reflection->getPropAtributos();
    }

    public function getTable() {
        return $this->tabela;
    }

    public function getColunas(Array $exceptionLoad = null) {
        if (count($exceptionLoad) > 0) {
            $colunas = explode(',', $this->colunas);
            foreach ($exceptionLoad as $atributo => $value) {
                if ($value === false && isset($this->propAtributos[$atributo]['Colmap'])) {
                    $key = array_search($this->propAtributos[$atributo]['Colmap'], $colunas);
                    if ($key !== false) {
                        unset($colunas[$key]);
                    }
                }
            }
            return implode(',', $colunas);
        }

        return $this->colunas;
    }

    public function getIdColmap() {
        return $this->propAtributos['id']['Colmap'];
    }

    private function order($dados = array()) {

        $colmap = $this->propAtributos[$dados[0]]['Colmap'];

        $pesistencia = null;
        if (isset($this->propAtributos[$dados[0]]['Persistence'])) {
            $pesistencia = $this->propAtributos[$dados[0]]['Persistence'];
        }

        $order = '';
        if (isset($dados[1])) {
            $order = $dados[1];
        }

        $orderby = $colmap;
        if ($order !== '') {
            $orderby .= ' ' . $order;
        }

        unset($colmap);
        unset($pesistencia);
        unset($dados);

        return $orderby;
    }

    private function getOrderby($orderby = null) {

        if ($orderby !== null) {

            $mutiplasColunas = strpos($orderby, ",");

            if ($mutiplasColunas) {

                $arrayOrderBy = explode(",", str_replace(" ", "", $orderby));

                foreach ($arrayOrderBy as $orderByAtributo) {

                    $order = strpos($orderByAtributo, ":");

                    if ($order) {
                        $dados = explode(":", $orderByAtributo);
                        $colunas[] = $this->order($dados);
                    } else {
                        $dados[0] = $orderByAtributo;
                        $colunas[] = $this->order($dados);
                    }

                    unset($colmap);
                    unset($order);
                }

                $colunas = implode(", ", $colunas);
                $orderby = "ORDER BY {$colunas}";

                unset($colunas);
            } else {

                $colmap = "";
                $order = strpos($orderby, ':');
                if ($order) {
                    $dados = explode(':', $orderby);
                    $orderby = 'ORDER BY ' . $this->order($dados);
                } else {
                    $dados[0] = $orderby;
                    $orderby = 'ORDER BY ' . $this->order($dados);
                }

                unset($colmap);
                unset($order);
            }
        } else {
            $orderby = "";
        }

        return $orderby;
    }

    /**
     * Realiza a operação de select no banco de dados
     * Retorna 1 linha da tabela
     * @param type $query
     * @param array $dados
     * @return boolean 
     */
    public function select($query, array $dados = null) {
        try {

            $prepare = $this->conn->prepare($query);

            if ($dados != null) {
                foreach ($dados as $indice => $value) {
                    $prepare->bindValue(":$indice", $value);
                }
            }

            $prepare->execute();
            $prepare->setFetchMode(PDO::FETCH_ASSOC);
            $row = $prepare->fetch();
            return $row;
        } catch (Exception $e) {
            // Gravar Log
            LogErroORM::gerarLogSelect($e->getMessage(), $query, $dados);
            return false;
        }
    }

    /**
     * Realiza a operação de select no banco de dados
     * Retorna multiplas linha da tabela
     * @param type $query
     * @param array $dados
     * @return boolean 
     */
    public function selectAll($query, array $dados = null) {

        try {
            $prepare = $this->conn->prepare($query);

            if ($dados != null) {
                foreach ($dados as $indice => $value) {
                    $prepare->bindValue(":$indice", $value);
                }
            }

            $prepare->execute();
            $prepare->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $prepare->fetchAll();
            return $rows;
        } catch (Exception $e) {
            // Gravar Log
            LogErroORM::gerarLogSelect($e->getMessage(), $query, $dados);
            return false;
        }
    }

    /**
     * Realiza a operação de select no banco de dados
     * Retorna multiplas linha da tabela
     * @param type $query
     * @param array $dados
     * @return boolean 
     */
    public function selectObjectAll($colunas = null, $where = null, $orderby = null, array $dados = null) {

        if ($colunas !== null) {

            $atributos = explode(",", $colunas);
            $arrayColunas = array();

            foreach ($atributos as $atributo) {
                $colmap = $this->propAtributos[$atributo]['Colmap'];
                if ($colmap) {
                    $arrayColunas[] = $colmap;
                }
                unset($colmap);
            }
            unset($atributos);

            $colunas = implode(",", $arrayColunas);
            unset($arrayColunas);
        } else {
            $colunas = $this->colunas;
        }

        $where = ($where !== null) ? "WHERE {$where}" : "";
        $orderby = $this->getOrderby($orderby);
        $query = "SELECT {$colunas} FROM {$this->tabela} $where {$orderby}";

        try {
            $prepare = $this->conn->prepare($query);

            if ($dados != null) {
                foreach ($dados as $indice => $value) {
                    $prepare->bindValue(":$indice", $value);
                }
            }

            $prepare->execute();
            $prepare->setFetchMode(PDO::FETCH_OBJ);
            $objetos = $prepare->fetchAll();
            return $objetos;
        } catch (Exception $e) {
            // Gravar Log
            LogErroORM::gerarLogSelect($e->getMessage(), $query, $dados);
            return false;
        }
    }

    /**
     * Lista uma collection de objetos
     * @param type $where
     * @param type $objectCollection
     * @return boolean
     */
    public function obter($where, $objectCollection = null, $exception = null) {

        $where = "WHERE {$where}";
        if (isset($exception['load'])) {
            $query = "SELECT {$this->getColunas($exception['load'])} FROM {$this->tabela} $where";
        } else {
            $query = "SELECT {$this->colunas} FROM {$this->tabela} $where";
        }
        $result = $this->select($query);

        if (!$result) {
            return false;
        }

        return $this->loadObject($result, $objectCollection, $exception);
    }

    /**
     * Retorna um unico objeto 
     * @param type $id
     * @param type $objectCollection
     * @return type
     */
    public function obterPorId($id, $objectCollection = null, $exception = null) {

        # Pegar colmap do id
        $ide_colmap = $this->propAtributos['id']['Colmap'];

        $AND = (isset($exception['and'])) ? 'AND ' . $exception['and'] : '';

        # cria query
        if (isset($exception['load'])) {
            $query = "SELECT {$this->getColunas($exception['load'])} FROM {$this->tabela} WHERE {$ide_colmap} = :id {$AND}";
        } else {
            $query = "SELECT {$this->colunas} FROM {$this->tabela} WHERE {$ide_colmap} = :id {$AND}";
        }

        # Executar query
        $dados['id'] = $id;
        $objeto = $this->select($query, $dados);

        # Retorna objeto
        return $this->loadObject($objeto, $objectCollection, $exception);
    }

    /**
     * Retorna objeto por id sem formatacao
     * @param type $id
     * @param type $objectCollection
     * @return type
     */
    private function obterPorIdUnFormatted($id, $objectCollection = null, $exception = null) {

        # Pegar colmap do id
        $ide_colmap = $this->propAtributos['id']['Colmap'];

        # cria query
        $query = "SELECT {$this->colunas} FROM {$this->tabela} WHERE {$ide_colmap} = :id";
        # Executar query
        $dados['id'] = $id;
        $objeto = $this->select($query, $dados);

        # Retorna objeto
        return $this->loadObjectUnFormatted($objeto, $objectCollection, $exception);
    }

    /**
     * Lista uma collection de objetos
     * @param type $where
     * @param type $objectCollection
     * @return boolean
     */
    public function listar($where = null, $orderby = null, $objectCollection = null, $exception = null, $offset = null, $limit = null) {

        $limit = ($limit !== null) ? $limit : LIMIT;
        $offset = ($offset !== null) ? 'LIMIT ' . $limit . ' OFFSET ' . $offset : '';

        if ($where != null) {
            $where = "WHERE {$where}";

            if ($exception !== null) {
                if (isset($exception['select'][$this->reflection->getClass()])) {
                    $where .= ' AND ' . $exception['select'][$this->reflection->getClass()];
                }
            }
        } else {
            $where = "";
        }

        $orderby = $this->getOrderby($orderby);
        if (isset($exception['load'])) {
            $query = "SELECT {$this->getColunas($exception['load'])} FROM {$this->tabela} {$where} {$orderby} {$offset}";
        } else {
            $query = "SELECT {$this->colunas} FROM {$this->tabela} {$where} {$orderby} {$offset}";
        }

        $result = $this->selectAll($query);

        if (!$result || count($result) == 0) {
            return false;
        }

        $collection = array();
        foreach ($result as $objeto) {
            $collection[] = $this->loadObject($objeto, $objectCollection, $exception);
        }

        return $collection;
    }

    /**
     * Total de registro de uma tabela
     * @param type $where
     * @return total
     */
    public function totalRegistro($where = null) {
        $where = ($where != null) ? "WHERE {$where}" : '';
        $query = "SELECT count({$this->getIdColmap()}) AS total FROM {$this->getTable()} {$where}";
        $totalRegistros = $this->select($query);
        return $totalRegistros['total'];
    }

    /**
     * Efetuar a soma de um atributo de uma tabela
     * @param type $coluna
     * @param type $where
     * @return total
     */
    public function somar($atributo, $where = null) {
        # Atributo existe um Colmap
        if (!isset($this->propAtributos[$atributo]['Colmap'])) {
            return false;
        }
        # pegar valor da coluna
        $coluna = $this->propAtributos[$atributo]['Colmap'];
        # Definir WHERE
        $where = ($where !== null) ? "WHERE {$where}" : '';
        $query = "SELECT Sum({$coluna}) AS total FROM {$this->getTable()} {$where}";
        $totalRegistros = $this->select($query);
        return ($totalRegistros['total'] === NULL) ? 0 : $totalRegistros['total'];
    }

    /**
     * Lista uma collection de objetos sem formatação
     * @param type $where
     * @param type $objectCollection
     * @return boolean
     */
    private function listarUnFormatted($where = null, $orderby = null, $objectCollection = null, $exception = null) {

        if ($where != null) {
            $where = "WHERE {$where}";

            if ($exception !== null) {
                if (isset($exception['select'][$this->reflection->getClass()])) {
                    $where .= ' AND ' . $exception['select'][$this->reflection->getClass()];
                }
            }
        } else {
            $where = "";
        }

        $orderby = $this->getOrderby($orderby);

        $query = "SELECT {$this->colunas} FROM {$this->tabela} {$where} {$orderby}";
        $result = $this->selectAll($query);

        if (!$result || count($result) == 0) {
            return false;
        }

        $collection = array();
        foreach ($result as $objeto) {
            $collection[] = $this->loadObjectUnFormatted($objeto, $objectCollection, $exception);
        }

        return $collection;
    }

    /**
     * Converte objeto em array
     * @param type $object
     * @return type 
     */
    public function objectToArray($object) {

        $dados = array();

        foreach ($this->reflection->getAtributos() as $atributo) {

            $getValue = 'get' . ucfirst($atributo);

            if ($object->$getValue() !== null) {

                if (is_array($object->$getValue())) {

                    $array = $object->$getValue();

                    if (is_object($array[0])) {

                        $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM(get_class($array[0])));

                        foreach ($array as $objeto) {
                            $arrayObjeto[] = $strategy->objectToArray($objeto);
                        }

                        $dados[$atributo] = $arrayObjeto;
                    } else {
                        $dados[$atributo] = $object->$getValue();
                    }

                    unset($array);
                } else {
                    if (!is_int($object->$getValue())) {
                        $dados[$atributo] = utf8_encode($object->$getValue());
                    } else {
                        $dados[$atributo] = $object->$getValue();
                    }
                }
            }
        }

        return $dados;
    }

    /**
     * Converte objeto em array colmap
     * @param type $object
     * @return type 
     */
    public function objectToArrayColmap($object) {

        $dados = array();

        foreach ($this->propAtributos as $atributo => $propriedades) {

            $getValue = 'get' . ucfirst($atributo);

            if ($object->$getValue() !== null) {

                $colmap = (isset($propriedades['Colmap'])) ? $propriedades['Colmap'] : false;

                if (!$colmap) {

                    if (is_array($object->$getValue())) {

                        if (isset($propriedades['OneToMany'])) {

                            $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['OneToMany']['objeto']));

                            foreach ($object->$getValue() as $key => $item) {
                                if (is_object($item)) {
                                    $dados[$atributo][$key] = $strategy->objectToArrayColmap($item);
                                } elseif (is_array($item)) {
                                    $dados[$atributo][$key] = $strategy->atributoToColmap($item);
                                }
                            }

                            unset($strategy);
                        } elseif (isset($propriedades['ManyToMany'])) {

                            $objectGetValue = $object->$getValue();

                            if (isset($objectGetValue[0])) {
                                unset($objectGetValue);

                                foreach ($object->$getValue() as $key => $item) {
                                    if (is_object($item)) {
                                        $dados[$atributo][$key] = $item->getId();
                                    } else {
                                        $dados[$atributo] = $object->$getValue();
                                        break;
                                    }
                                }
                            } else {
                                unset($objectGetValue);
                                $dados[$atributo] = $object->$getValue();
                            }
                        }
                    } else {
                        $dados[$atributo] = $object->$getValue();
                    }
                } else {
                    $dados[$colmap] = $object->$getValue();
                }
            }
        }

        # Limpar memoria
        unset($object);
        # retornar dados
        return $dados;
    }

    /**
     * Converte objeto em array
     * @param type $object
     * @return type 
     */
    public function objectToJson($object, array $arrayAdd = null) {

        $arrayObject = $this->objectToArray($object);

        if ($arrayAdd !== null) {
            foreach ($arrayAdd as $key => $value) {
                $arrayObject[$key] = $value;
            }
        }

        $json = json_encode($arrayObject);

        return $json;
    }

    /**
     * Converte array de atributos em array de colmap
     * @param type $array
     * @return type 
     */
    public function atributoToColmap($array) {

        $dados = array();

        foreach ($this->propAtributos as $atributo => $propriedades) {

            if (isset($array[$atributo])) {

                $colmap = (isset($propriedades['Colmap'])) ? $propriedades['Colmap'] : false;
                if (!$colmap) {

                    if (is_array($array[$atributo])) {

                        if (isset($propriedades['OneToMany'])) {
                            $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['OneToMany']['objeto']));
                            foreach ($array[$atributo] as $key => $item) {
                                $dados[$atributo][$key] = $strategy->atributoToColmap($item);
                            }
                        } elseif (isset($propriedades['ManyToMany'])) {
                            $dados[$atributo] = $array[$atributo];
                        }
                    }
                } else {
                    $dados[$colmap] = $array[$atributo];
                }
            }
        }

        # limpar memoria
        unset($array);

        return $dados;
    }

    /**
     * Metodo salvar - direciona para atualizar ou inserir
     * @param type $dados
     * @return type
     */
    public function salvar($dados, $objectResult = null, array $exception = null) {

        if (is_array($dados)) {
            $dados = $this->atributoToColmap($dados);
        } elseif (is_object($dados)) {
            $dados = $this->objectToArrayColmap($dados);
        } else {
            $result = array();
            $result[0] = false;
            $result[1] = $dados;
            $result[2]['post'] = 'Não pode ser salvo (Para salvar precisa passar um objto ou um array)';
            return $result;
        }

        $id_serial = (isset($this->propAtributos['id']['Serial'])) ? true : false;
        $id_colmap = $this->propAtributos['id']['Colmap'];

        if (isset($dados[$id_colmap]) && $dados[$id_colmap] != "") { // existe id em $dados
            if (!$id_serial) { // id não é serial
                $objeto = $this->obterPorId($dados[$id_colmap]);

                if (!$objeto) {
                    return $this->inserir($dados, $objectResult);
                } else {
                    return $this->atualizar($dados, $objectResult, $exception);
                }
            } else { // id é serial
                return $this->atualizar($dados, $objectResult, $exception);
            }
        } else { // Não existe id
            return $this->inserir($dados, $objectResult);
        }
    }

    /**
     * Executa query de delete de acordo as configurações
     *  passadas ignorando o objeto instanciado, os dados são passado 
     *  no formato de array onde o indice é o nome do campo no banco de dados
     *  e value o valor buscado para o mesmo
     * @example $dados['ide_qualquer'] = 1
     * @param string $from
     * @param string $where
     * @param array $dados
     * @return boolean
     */
    public function deleteQuery($from, $where, Array $dados = null) {

        # Pegar colmap do id
        $query = "DELETE FROM {$from} WHERE $where";

        // Deletar intens da base de dados
        try {

            #iniciar transação
            $this->conn->beginTransaction();

            // Preparar query
            $prepare = $this->conn->prepare($query);
            if ($dados !== null) {
                foreach ($dados as $key => $value) {
                    $prepare->bindValue(":{$key}", $value);
                }
            }

            # executar query
            $prepare->execute();

            # Finalizar transação
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            LogErroORM::gerarLogDelete($e->getMessage(), $query, $dados);
            return false;
        }

        return true;
    }

    /**
     * Deletar dados na base de dados
     * @param type $id
     * @return type
     */
    public function delete($id) {

        # Pegar colmap do id
        $ide_colmap = $this->propAtributos['id']['Colmap'];
        $query = "DELETE FROM {$this->tabela} WHERE {$ide_colmap} = :{$ide_colmap}";

        // Deletar intens da base de dados
        try {

            #iniciar transação
            $this->conn->beginTransaction();

            // Preparar query
            $prepare = $this->conn->prepare($query);
            $prepare->bindValue(":$ide_colmap", $id);
            # executar query
            $prepare->execute();

            # Finalizar transação
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            $dados[$ide_colmap] = $id;
            LogErroORM::gerarLogDelete($e->getMessage(), $query, $dados);
            return false;
        }

        return true;
    }

    /**
     * Deletar uma coleção de dados na base de dados
     * @param type $id
     * @return type
     */
    public function deleteAll(Array $ids) {

        # Pegar colmap do id
        $ide_colmap = $this->propAtributos['id']['Colmap'];
        $query = "DELETE FROM {$this->tabela} WHERE {$ide_colmap} = :{$ide_colmap}";

        // Deletar intens da base de dados
        try {

            #iniciar transação
            $this->conn->beginTransaction();

            foreach ($ids as $id) {
                $prepare = $this->conn->prepare($query);
                $prepare->bindValue(":$ide_colmap", $id);
                # executar query
                $prepare->execute();
            }

            // Preparar query
            # Finalizar transação
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            $dados[$ide_colmap] = $ids;
            LogErroORM::gerarLogDelete($e->getMessage(), $query, $dados);
            return false;
        }

        return true;
    }

    /**
     * Deletar uma coleção de dados na base de dados
     * @param type $id
     * @return type
     */
    public function excluir($where, $dados = null) {

        $query = "DELETE FROM {$this->tabela} WHERE {$where}";

        // Deletar intens da base de dados
        try {

            #iniciar transação
            $this->conn->beginTransaction();

            # Preparar query
            $prepare = $this->conn->prepare($query);

            # Existir dados
            if ($dados !== null) {
                foreach ($dados as $key => $value) {
                    $prepare->bindValue(":{$key}", $value);
                }
            }

            # executar query
            $prepare->execute();

            // Preparar query
            # Finalizar transação
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            LogErroORM::gerarLogDelete($e->getMessage(), $query, $dados);
            return false;
        }

        return true;
    }

    private function loadObjectPersistInsert($array) {

        $objeto = clone $this->reflection->getObjClass();
        $erro = array();

        $id_colmap = $this->propAtributos['id']['Colmap'];

        foreach ($this->propAtributos as $atributo => $propriedades) {

            $colmap = (isset($propriedades['Colmap'])) ? $propriedades['Colmap'] : false;
            $persistence = (isset($propriedades['Persistence'])) ? $propriedades['Persistence'] : false;
            $setValue = 'set' . ucfirst($atributo);

            if ($colmap != false) {

                if (isset($array[$colmap]) && $array[$colmap] !== "" && $array[$colmap] !== null) {

                    if ($persistence !== false) {

                        $pesist = $persistence->type;
                        $valide = PersistenceORM::$pesist($persistence, $array[$colmap]);

                        if (!$valide[0]) {
                            $erro[$atributo] = $valide[1];
                            $objeto->$setValue($array[$colmap]);
                        } else {
                            $objeto->$setValue($valide[1]);
                        }
                    } else {
                        $objeto->$setValue($array[$colmap]);
                    }
                } else {

                    if ($persistence !== false) {

                        $pesist = $persistence->type;
                        $valide = PersistenceORM::$pesist($persistence, "");

                        if (!$valide[0]) {
                            $erro[$atributo] = $valide[1];
                        }
                    }
                }
            } else {

                if (isset($array[$atributo]) && is_array($array[$atributo])) {

                    if (isset($propriedades['OneToMany'])) {

                        $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['OneToMany']['objeto']));
                        $id_atributo = $strategy->reflection->getAtributo($id_colmap);

                        foreach ($array[$atributo] as $key => $item) {

                            $valideOneToMany = $strategy->loadObjectPersistInsert($item);

                            if (!$valideOneToMany[0]) {

                                if (isset($valideOneToMany[2][$id_atributo])) {
                                    unset($valideOneToMany[2][$id_atributo]);
                                }

                                if (count($valideOneToMany[2]) > 0) {
                                    $erro[$atributo][$key] = $valideOneToMany[2];
                                }
                            }

                            $objeto->$setValue($valideOneToMany[1]);
                        }
                    } elseif (isset($propriedades['ManyToMany'])) {
                        $objeto->$setValue($array[$atributo]);
                    }
                }
            }
        }

        $result = array();
        if (count($erro) > 0) {
            $result[0] = false;
            $result[1] = $objeto;
            $result[2] = $erro;
        } else {
            $result[0] = true;
            $result[1] = $objeto;
        }

        return $result;
    }

    private function loadObjectPersistUpdate($array) {

        $objeto = clone $this->reflection->getObjClass();
        $erro = array();

        foreach ($this->propAtributos as $atributo => $propriedades) {

            $colmap = (isset($propriedades['Colmap'])) ? $propriedades['Colmap'] : false;
            $persistence = (isset($propriedades['Persistence'])) ? $propriedades['Persistence'] : false;
            $setValue = 'set' . ucfirst($atributo);

            if ($colmap !== false) {

                if (isset($array[$colmap])) {

                    if ($array[$colmap] !== null) {

                        if ($persistence != false) {

                            $pesist = $persistence->type;
                            $valide = PersistenceORM::$pesist($persistence, $array[$colmap]);
                            unset($pesist);

                            if (!$valide[0]) {
                                $erro[$atributo] = $valide[1];
                                $objeto->$setValue($array[$colmap]);
                            } else {
                                $objeto->$setValue($valide[1]);
                            }
                            unset($valide);
                        } else {
                            $objeto->$setValue($array[$colmap]);
                        }
                    }
                }
            } else {

                if (isset($array[$atributo]) && is_array($array[$atributo])) {

                    if (isset($propriedades['OneToMany'])) {
                        $objeto->$setValue($array[$atributo]);
                    }

                    if (isset($propriedades['ManyToMany'])) {
                        // montar array de collection
                        $objeto->$setValue($array[$atributo]);
                    }
                }
            }

            unset($colmap);
            unset($persistence);
            unset($setValue);
        }

        $result = array();
        if (count($erro) > 0) {
            $result[0] = false;
            $result[1] = $objeto;
            $result[2] = $erro;
        } else {
            $result[0] = true;
            $result[1] = $objeto;
        }
        unset($erro);
        unset($objeto);

        return $result;
    }

    /**
     * Carrega o objeto atravez do array passado
     * @param type $array
     *  null : não carrega, false: carrega id, true carrega obj
     * @param type $objectCollection
     * @return boolean
     */
    public function loadObject($array, $objectCollection = null, $exception = null) {

        if (!$array) {
            return false;
        }

        $objeto = clone $this->reflection->getObjClass();
        $id_colmap = $this->propAtributos['id']['Colmap'];

        foreach ($this->propAtributos as $atributo => $propriedades) {

            $selectLoad = true;
            $exceptionObject = null;

            if (isset($exception['load'][$atributo])) {
                if ($exception['load'][$atributo] === false) {
                    unset($exception['load'][$atributo]);
                    $selectLoad = false;
                } else {
                    $exceptionObject['load'] = $exception['load'][$atributo];
                }
            }
            if ($selectLoad) {
                $colmap = (isset($propriedades['Colmap'])) ? $propriedades['Colmap'] : false;
                $setValue = 'set' . ucfirst($atributo);

                # Se exite uma referencia do Colmap no atributo
                if ($colmap !== false) {

                    # Se existe um colmap e ele é diferente de '' e null
                    if (isset($array[$colmap]) && $array[$colmap] !== '' && $array[$colmap] !== null) {

                        if ($objectCollection === null || $objectCollection === false) {

                            if (isset($propriedades['Mask'])) {
                                $mask = $propriedades['Mask'];
                                $objeto->$setValue(MaskORM::$mask($array[$colmap]));
                                unset($mask);
                            } else {
                                $objeto->$setValue($array[$colmap]);
                            }
                        } elseif ($objectCollection === true) {

                            if (isset($propriedades['OneToOne'])) {

                                $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['OneToOne']['objeto']));

                                $object = $strategy->obterPorId($array[$colmap], false, $exceptionObject);

                                if (is_object($object)) {
                                    $objeto->$setValue($object);
                                }

                                unset($object);
                                unset($strategy);
                            } else {
                                if (isset($propriedades['Mask'])) {
                                    $mask = $propriedades['Mask'];
                                    $objeto->$setValue(MaskORM::$mask($array[$colmap]));
                                    unset($mask);
                                } else {
                                    $objeto->$setValue($array[$colmap]);
                                }
                            }
                        }
                    }
                } elseif ($objectCollection === true) {

                    if (isset($propriedades['OneToMany'])) {

                        $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['OneToMany']['objeto']));
                        if (isset($propriedades['OneToMany']['coluna'])) {
                            $listObjeto = $strategy->listar("{$propriedades['OneToMany']['coluna']} = '{$objeto->getId()}'", null, false, $exception);
                        } else {
                            $listObjeto = $strategy->listar("{$id_colmap} = '{$objeto->getId()}'", null, false, $exception);
                        }

                        if ($listObjeto != false) {
                            $objeto->$setValue($listObjeto);
                        }

                        unset($strategy);
                        unset($listObjeto);
                    } elseif (isset($propriedades['ManyToMany'])) {

                        $selectException = "";

                        if ($exception !== null) {
                            if (isset($exception['select'][$atributo])) {
                                $selectException = "AND " . $exception['select'][$atributo];
                            }
                        }

                        $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['ManyToMany']['objeto']));


                        if (isset($propriedades['ManyToMany']['coluna'])) {
                            $query = "SELECT {$propriedades['ManyToMany']['coluna']} FROM {$propriedades['ManyToMany']['table']} WHERE {$id_colmap} = '{$objeto->getId()}' {$selectException}";
                        } else {
                            $query = "SELECT {$strategy->getIdColmap()} FROM {$propriedades['ManyToMany']['table']} WHERE {$id_colmap} = '{$objeto->getId()}' {$selectException}";
                        }
                        unset($selectException);

                        $result = $this->selectAll($query);

                        unset($query);

                        if ($result !== false) {

                            $collection = array();
                            if (isset($propriedades['ManyToMany']['coluna'])) {
                                foreach ($result as $array) {
                                    $collection[] = $strategy->obterPorId($array[$propriedades['ManyToMany']['coluna']], false, $exceptionObject);
                                }
                            } else {
                                foreach ($result as $array) {
                                    $collection[] = $strategy->obterPorId($array[$strategy->getIdColmap()], false, $exceptionObject);
                                }
                            }

                            $objeto->$setValue($collection);

                            unset($collection);
                        }

                        unset($strategy);
                        unset($result);
                    }
                } elseif ($objectCollection === false) {

                    if (isset($propriedades['OneToMany'])) {

                        $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['OneToMany']['objeto']));

                        if (isset($propriedades['OneToMany']['coluna'])) {
                            $query = "SELECT {$strategy->getIdColmap()} FROM {$strategy->getTable()} WHERE {$propriedades['OneToMany']['coluna']} = '{$objeto->getId()}'";
                        } else {
                            $query = "SELECT {$strategy->getIdColmap()} FROM {$strategy->getTable()} WHERE {$id_colmap} = '{$objeto->getId()}'";
                        }

                        $listObjeto = $this->selectAll($query);
                        unset($query);
                        if ($listObjeto !== false) {
                            $arrayCollection = array();

                            foreach ($listObjeto as $ObjValue) {
                                $arrayCollection[] = $ObjValue[$strategy->getIdColmap()];
                            }

                            $objeto->$setValue($arrayCollection);
                            unset($arrayCollection);
                        }
                        unset($listObjeto);
                        unset($strategy);
                    } elseif (isset($propriedades['ManyToMany'])) {

                        $selectException = "";
                        if ($exception !== null) {
                            if (isset($exception['select'][$atributo])) {
                                $selectException = "AND " . $exception['select'][$atributo];
                            }
                        }

                        $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['ManyToMany']['objeto']));

                        if (isset($propriedades['ManyToMany']['coluna'])) {
                            $query = "SELECT {$propriedades['ManyToMany']['coluna']} FROM {$propriedades['ManyToMany']['table']} WHERE {$id_colmap} = {$objeto->getId()} {$selectException}";
                        } else {
                            $query = "SELECT {$strategy->getIdColmap()} FROM {$propriedades['ManyToMany']['table']} WHERE {$id_colmap} = {$objeto->getId()} {$selectException}";
                        }
                        unset($selectException);
                        $result = $this->selectAll($query);
                        unset($query);

                        if ($result !== false) {

                            $collection = array();

                            if (isset($propriedades['ManyToMany']['coluna'])) {
                                foreach ($result as $array) {
                                    $collection[] = $array[$propriedades['ManyToMany']['coluna']];
                                }
                            } else {
                                foreach ($result as $array) {
                                    $collection[] = $array[$strategy->getIdColmap()];
                                }
                            }
                            $objeto->$setValue($collection);
                            unset($collection);
                        }

                        unset($result);
                        unset($strategy);
                    }
                }
            }
        }
        unset($id_colmap);
        # Retorna objeto
        return $objeto;
    }

    /**
     * Carrega o objeto sem formatacao atravez do array passado
     * @param type $array
     *  null : não carrega, false: carrega id, true carrega obj
     * @param type $objectCollection
     * @return boolean
     */
    private function loadObjectUnFormatted($array, $objectCollection = null, $exception = null) {

        if (!$array) {
            return false;
        }

        $objeto = clone $this->reflection->getObjClass();
        $id_colmap = $this->propAtributos['id']['Colmap'];

        foreach ($this->propAtributos as $atributo => $propriedades) {

            $colmap = (isset($propriedades['Colmap'])) ? $propriedades['Colmap'] : false;
            $setValue = 'set' . ucfirst($atributo);

            if ($colmap !== false) {

                if (isset($array[$colmap]) && $array[$colmap] !== "" && $array[$colmap] !== null) {

                    if ($objectCollection === null || $objectCollection === false) {
                        $objeto->$setValue($array[$colmap]);
                    } elseif ($objectCollection === true) {

                        if (isset($propriedades['OneToOne'])) {

                            $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['OneToOne']['objeto']));

                            $object = $strategy->obterPorIdUnFormatted($array[$colmap], false);

                            if (is_object($object)) {
                                $objeto->$setValue($object);
                            }

                            unset($object);
                            unset($strategy);
                        } else {
                            $objeto->$setValue($array[$colmap]);
                        }
                    }
                }
            } elseif ($objectCollection === true) {

                if (isset($propriedades['OneToMany'])) {

                    $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['OneToMany']['objeto']));

                    if (isset($propriedades['OneToMany']['coluna'])) {
                        $listObjeto = $strategy->listarUnFormatted("{$propriedades['OneToMany']['coluna']} = '{$objeto->getId()}'", null, false, $exception);
                    } else {
                        $listObjeto = $strategy->listarUnFormatted("{$id_colmap} = '{$objeto->getId()}'", null, false, $exception);
                    }

                    if ($listObjeto != false) {
                        $objeto->$setValue($listObjeto);
                    }

                    unset($strategy);
                    unset($listObjeto);
                } elseif (isset($propriedades['ManyToMany'])) {

                    $selectException = "";

                    if ($exception !== null) {
                        if (isset($exception['select'][$atributo])) {
                            $selectException = "AND " . $exception['select'][$atributo];
                        }
                    }


                    $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['ManyToMany']['objeto']));

                    if (isset($propriedades['ManyToMany']['coluna'])) {
                        $query = "SELECT {$propriedades['ManyToMany']['coluna']} FROM {$propriedades['ManyToMany']['table']} WHERE {$id_colmap} = '{$objeto->getId()}' {$selectException}";
                    } else {
                        $query = "SELECT {$strategy->getIdColmap()} FROM {$propriedades['ManyToMany']['table']} WHERE {$id_colmap} = '{$objeto->getId()}' {$selectException}";
                    }
                    unset($selectException);

                    $result = $this->selectAll($query);
                    unset($query);

                    if ($result !== false) {

                        $collection = array();
                        if (isset($propriedades['ManyToMany']['coluna'])) {
                            foreach ($result as $array) {
                                $collection[] = $strategy->obterPorIdUnFormatted($array[$propriedades['ManyToMany']['coluna']], false);
                            }
                        } else {
                            foreach ($result as $array) {
                                $collection[] = $strategy->obterPorIdUnFormatted($array[$strategy->getIdColmap()], false);
                            }
                        }

                        $objeto->$setValue($collection);

                        unset($collection);
                    }

                    unset($strategy);
                    unset($result);
                }
            } elseif ($objectCollection === false) {

                if (isset($propriedades['OneToMany'])) {

                    $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['OneToMany']['objeto']));

                    if (isset($propriedades['OneToMany']['coluna'])) {
                        $query = "SELECT {$strategy->getIdColmap()} FROM {$strategy->getTable()} WHERE {$propriedades['OneToMany']['coluna']} = '{$objeto->getId()}'";
                    } else {
                        $query = "SELECT {$strategy->getIdColmap()} FROM {$strategy->getTable()} WHERE {$id_colmap} = '{$objeto->getId()}'";
                    }

                    $listObjeto = $this->selectAll($query);
                    unset($query);

                    if ($listObjeto !== false) {
                        $arrayCollection = array();
                        foreach ($listObjeto as $ObjValue) {
                            $arrayCollection[] = $ObjValue[$strategy->getIdColmap()];
                        }
                        $objeto->$setValue($arrayCollection);
                        unset($arrayCollection);
                    }
                    unset($listObjeto);
                    unset($strategy);
                } elseif (isset($propriedades['ManyToMany'])) {

                    $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($propriedades['ManyToMany']['objeto']));

                    if (isset($propriedades['ManyToMany']['coluna'])) {
                        $query = "SELECT {$propriedades['ManyToMany']['coluna']} FROM {$propriedades['ManyToMany']['table']} WHERE {$id_colmap} = {$objeto->getId()}";
                    } else {
                        $query = "SELECT {$strategy->getIdColmap()} FROM {$propriedades['ManyToMany']['table']} WHERE {$id_colmap} = {$objeto->getId()}";
                    }

                    $result = $this->selectAll($query);
                    unset($query);

                    if ($result !== false) {

                        $collection = array();
                        if (isset($propriedades['ManyToMany']['coluna'])) {
                            foreach ($result as $array) {
                                $collection[] = $array[$propriedades['ManyToMany']['coluna']];
                            }
                        } else {
                            foreach ($result as $array) {
                                $collection[] = $array[$strategy->getIdColmap()];
                            }
                        }

                        $objeto->$setValue($collection);
                        unset($collection);
                    }

                    unset($result);
                    unset($strategy);
                }
            }
        }
        unset($id_colmap);
        # Retorna objeto
        return $objeto;
    }

    /**
     * Montar array para o metodo insert
     * @param type $dados
     * @param type $objeto
     * @return type
     */
    private function mountArrayInsert($dados, &$objeto) {

        foreach ($this->propAtributos as $atributo => $propriedades) {

            $colmap = (isset($propriedades['Colmap'])) ? $propriedades['Colmap'] : false;

            if (!$colmap) {

                if (isset($propriedades['OneToMany'])) {
                    if (isset($dados[$atributo]) && $dados[$atributo] !== "" && $dados[$atributo] !== null) {
                        $OneToMany[$atributo] = $dados[$atributo];
                    }
                } elseif (isset($propriedades['ManyToMany'])) {
                    if (isset($dados[$atributo]) && $dados[$atributo] !== "" && $dados[$atributo] !== null) {
                        $ManyToMany[$atributo] = $dados[$atributo];
                    }
                }
            } else {

                $getValue = 'get' . ucfirst($atributo);
                $get = $objeto->$getValue();

                if ($get != null) {
                    $entity[$colmap] = $get;
                }

                unset($getValue);
                unset($get);
            }

            unset($colmap);
        }

        $result = array();
        if (!isset($entity) && !isset($ManyToMany) && !isset($OneToMany)) {
            $result[0] = false;
        } else {
            $result[0] = true;

            if (isset($entity)) {
                $result[1]['entity'] = $entity;
                unset($entity);
            }

            if (isset($OneToMany)) {
                $result[1]['OneToMany'] = $OneToMany;
                unset($OneToMany);
            }

            if (isset($ManyToMany)) {
                $result[1]['ManyToMany'] = $ManyToMany;
                unset($ManyToMany);
            }
        }

        return $result;
    }

    /**
     * Inserir dados na base de dados
     * @param type $array
     * @return string
     */
    private function inserir($array, $objectResult = null) {

        $result = $this->loadObjectPersistInsert($array);

        if (!$result[0]) {
            return $result;
        }

        $objeto = $result[1];
        $dados = $this->mountArrayInsert($array, $objeto);

        if (!$dados[0]) {
            $result[0] = false;
            $result[2]['null'] = "Não existe dados a ser inserido";
            return $result;
        }

        // Campos a serem inserido na base de dados
        $inserir = $dados[1];

        //nome de referencia do id da tabela
        $id_colmap = $this->getIdColmap();

        if (isset($inserir['entity'])) {

            $campos = implode(", ", array_keys($inserir['entity']));
            $values = ":" . implode(", :", array_keys($inserir['entity']));
            $insert_query = "INSERT INTO {$this->tabela} ({$campos}) VALUES ({$values})";

            unset($campos);
            unset($values);

            if (isset($inserir['OneToMany'])) {

                foreach ($inserir['OneToMany'] as $atributo => $values) {

                    $strategy = new MySqlPdoStrategy($this->conn, new ReflectionORM($this->propAtributos[$atributo]['OneToMany']['objeto']));

                    foreach ($values as $key => $row) {

                        $k = $row;
                        $k[$id_colmap] = "";
                        $campos = implode(", ", array_keys($k));
                        $values = ":" . implode(", :", array_keys($k));

                        # Query
                        $insertOneToMany[$atributo][$key]['@query'] = "INSERT INTO " . $strategy->getTable() . " ({$campos}) VALUES ({$values})";

                        # Montar BindValue
                        foreach ($row as $atrib => $value) {
                            $insertOneToMany[$atributo][$key][$atrib] = $value;
                        }

                        # Limpar variaveis
                        unset($k);
                        unset($campos);
                        unset($values);
                    }

                    unset($strategy);
                }
            }

            if (isset($inserir['ManyToMany'])) {

                foreach ($inserir['ManyToMany'] as $atributo => $values) {

                    $relationship = $this->propAtributos[$atributo]['ManyToMany'];
                    $reflectionRelationship = new ReflectionORM($relationship['objeto']);
                    $id_relationship = $reflectionRelationship->getPropAnnotations('id', '@Colmap');
                    $insertManyToMany[$atributo]['@query'] = "INSERT INTO {$relationship['table']} ({$id_colmap},{$id_relationship}) VALUES (:{$id_colmap},:{$id_relationship})";
                    foreach ($values as $value) {
                        $insertManyToMany[$atributo][$id_relationship][] = $value;
                    }
                    unset($relationship);
                    unset($reflectionRelationship);
                    unset($id_relationship);
                }
            }
        } else {
            $result[0] = false;
            $result[2]['null'][] = "Não existe dados a ser inserido";
            return $result;
        }

        // Inserir na base de dados
        try {

            #iniciar transação
            $this->conn->beginTransaction();

            $prepare = $this->conn->prepare($insert_query);

            foreach ($inserir['entity'] as $k => $v) {
                $prepare->bindValue(":$k", $v);
            }

            $prepare->execute();

            $id_entity = $this->conn->lastInsertId();

            if (isset($insertOneToMany)) {

                foreach ($insertOneToMany as $atributo => $array) {
                    foreach ($array as $colunas) {
                        $prepare = $this->conn->prepare($colunas['@query']);
                        $prepare->bindValue(":{$id_colmap}", $id_entity);
                        foreach ($colunas as $indice => $value) {
                            if ($indice != "@query") {
                                $prepare->bindValue(":{$indice}", $value);
                            }
                        }
                        $prepare->execute();
                    }
                }
            }

            if (isset($insertManyToMany)) {

                foreach ($insertManyToMany as $atributo => $array) {
                    foreach ($array as $indice => $idRelationship) {
                        if ($indice != "@query") {
                            foreach ($idRelationship as $value) {
                                // Preparar query
                                $prepare = $this->conn->prepare($array['@query']);
                                $prepare->bindValue(":$id_colmap", $id_entity);
                                $prepare->bindValue(":$indice", $value);
                                $prepare->execute();
                            }
                        }
                    }
                }
            }

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            $result[0] = false;
            $result[2]['sql'] = $e->getMessage();
            // Gravar Log
            $dados = $inserir['entity'];
            $collection = (isset($insertManyToMany)) ? $insertManyToMany : null;

            SatelliteHelper::gerarLogInsert($conn, $e->getMessage(), $insert_query, $dados, $collection);
            LogErroORM::gerarLogInsert($e->getMessage(), $insert_query, $dados, $collection);
            return $result;
        }
        $result[1] = $this->obterPorId($id_entity, $objectResult);
        return $result;
    }

    /**
     * Montar array para ser usado para update
     * @param type $dados
     * @param type $objeto
     * @return boolean|array
     */
    private function mountArrayUpdate($dados, &$objeto) {

        # Pegar objeto no banco
        $object = $this->obterPorIdUnFormatted($objeto->getId());
        # Pegar objeto com objectLoad
        $objectLoad = $this->obterPorIdUnFormatted($objeto->getId(), true);

        // verificar dados para update
        foreach ($this->propAtributos as $atributo => $propriedades) {
            $getValue = 'get' . ucfirst($atributo);
            if ($objeto->$getValue() !== null) {

                $colmap = (isset($propriedades['Colmap'])) ? $propriedades['Colmap'] : false;

                if (!$colmap) { // Se colmap for false
                    if (isset($propriedades['OneToMany'])) {
                        
                    } elseif (isset($propriedades['ManyToMany'])) {

                        if (isset($dados[$atributo])) {

                            $objLoadGetValue = $objectLoad->$getValue();

                            if (isset($dados[$atributo][0])) {

                                $flag = 0;

                                if (isset($objLoadGetValue[0])) {
                                    unset($objLoadGetValue);

                                    if (count($objectLoad->$getValue()) != count($dados[$atributo])) {
                                        $flag++;
                                    }

                                    foreach ($objectLoad->$getValue() as $objLoad) {
                                        $arrayObj[] = $objLoad->getId();
                                        if (!in_array($objLoad->getId(), $dados[$atributo])) {
                                            $flag++;
                                        }
                                    }

                                    foreach ($dados[$atributo] as $value) {
                                        if (!in_array($value, $arrayObj)) {
                                            $flag++;
                                        }
                                    }
                                    unset($arrayObj);

                                    if ($flag > 0) {
                                        $collection[$atributo] = $dados[$atributo];
                                    }
                                } else {
                                    $collection[$atributo] = $dados[$atributo];
                                }

                                unset($flag);
                            } else {
                                if (isset($objLoadGetValue[0])) {
                                    unset($objLoadGetValue);
                                    $collection[$atributo] = array();
                                }
                            }
                        }
                    }
                } else { // se colmap não for false
                    if ($atributo === 'id') {

                        $id_serial = (isset($propriedades['Serial'])) ? true : false;

                        if (!$id_serial) {

                            $val = $objeto->$getValue();
                            $valBd = $object->$getValue();

                            if ($val !== $valBd) {
                                $campo[$colmap] = $val;
                            }
                        }
                    } else {

                        $val = $objeto->$getValue();
                        $valBd = $object->$getValue();
                        if ($val !== $valBd) {
                            $campo[$colmap] = $val;
                        }
                    }
                }

                unset($colmap);
            }
        }

        unset($object);
        unset($objectLoad);

        if (!isset($campo) && !isset($collection)) {
            return false;
        }

        $result = array();
        if (isset($campo)) {
            $result['colmap'] = $campo;
            unset($campo);
        }

        if (isset($collection)) {
            $result['collection'] = $collection;
            unset($collection);
        }

        return $result;
    }

    /**
     * Atualiza a entidade
     * @param type $dados
     * @return string
     */
    private function atualizar($dados, $objectResult = null, $exception = null) {

        $result = $this->loadObjectPersistUpdate($dados);

        if (!$result[0]) {
            return $result;
        }

        $update = $this->mountArrayUpdate($dados, $result[1]);

        if (!$update) {
            return $result;
        }

        // Id do objeto
        $id_entity = $result[1]->getId();

        # Pegar colmap do id
        $id_colmap = $this->propAtributos['id']['Colmap'];

        // Preparar query da entity
        if (isset($update['colmap'])) {
            foreach ($update['colmap'] as $key => $value) {
                $camposUpdate[] = "{$key} = :{$key}";
            }
            $campos = implode(",", $camposUpdate);
            unset($camposUpdate);
            $updateQueryEntity = "UPDATE {$this->tabela} SET {$campos} WHERE {$id_colmap} = {$id_entity}";
        }

        // Preparar query da collection
        if (isset($update['collection'])) {


            if (count($update['collection']) > 0) {

                foreach ($update['collection'] as $atributo => $campos) {

                    $relationship = $this->reflection->getPropAnnotations($atributo, '@Relationship');
                    $reflectionRelationship = new ReflectionORM($relationship->objeto);
                    $id_relationship = $reflectionRelationship->getPropAnnotations('id', '@Colmap');

                    # verificar exception e definir where do delete
                    $deleteException = (isset($exception['delete'][$atributo])) ? "AND " . $exception['delete'][$atributo] : "";
                    $collection[$atributo]['@query']['delete'] = "DELETE FROM {$relationship->table} WHERE {$id_colmap} = :{$id_colmap} {$deleteException}";
                    unset($deleteException);

                    if (count($update['collection'][$atributo]) > 0) {

                        if (isset($relationship->coluna)) {
                            $collection[$atributo]['@query']['insert'] = "INSERT INTO {$relationship->table} ({$id_colmap},{$relationship->coluna}) VALUES (:{$id_colmap},:{$relationship->coluna})";
                        } else {
                            $collection[$atributo]['@query']['insert'] = "INSERT INTO {$relationship->table} ({$id_colmap},{$id_relationship}) VALUES (:{$id_colmap},:{$id_relationship})";
                        }

                        foreach ($campos as $value) {
                            if (isset($relationship->coluna)) {
                                $collection[$atributo][$relationship->coluna][] = $value;
                            } else {
                                $collection[$atributo][$id_relationship][] = $value;
                            }
                        }
                    } else {
                        $collection[$atributo][$id_relationship] = array();
                    }

                    unset($relationship);
                    unset($reflectionRelationship);
                    unset($id_relationship);
                }
            }
        }

        // Inserir na base de dados
        try {

            #iniciar transação
            $this->conn->beginTransaction();

            // Preparar query da entity
            if (isset($update['colmap'])) {
                $prepare = $this->conn->prepare($updateQueryEntity);
                foreach ($update['colmap'] as $k => $v) {
                    $prepare->bindValue(":$k", $v);
                }
                $prepare->execute();
            }


            // Inserir Collections
            if (isset($collection)) {

                $chaves = array();
                foreach ($collection as $key => $value) {
                    $chaves[] = $key;
                }

                foreach ($chaves as $atrib) {

                    # Deletar collection
                    $prepare = $this->conn->prepare($collection[$atrib]['@query']['delete']);
                    $prepare->bindValue(":$id_colmap", $id_entity);
                    $prepare->execute();

                    foreach ($collection[$atrib] as $atributo => $array) {

                        if ($atributo != "@query") {
                            if (isset($array[0])) {

                                $prepare = $this->conn->prepare($collection[$atrib]['@query']['insert']);

                                foreach ($array as $idRelationship) {
                                    $prepare->bindValue(":$id_colmap", $id_entity);
                                    $prepare->bindValue(":$atributo", $idRelationship);
                                    $prepare->execute();
                                }
                            }
                        }
                    }
                }
            }

            # Finalizar transação
            $this->conn->commit();
        } catch (Exception $e) {

            $this->conn->rollBack();
            $result[0] = false;
            $result[2]['sql'] = $e->getMessage();

            if (isset($update['colmap'])) {
                $update_log['query'] = $updateQueryEntity;
                $update_log['colmap'] = $update['colmap'];
            } else {
                $update_log = null;
            }

            if (isset($collection)) {
                # Collection
                $collection_log = $collection;
                # Id do objeto
                $id_ref['id_entity'] = $id_entity;
                # ID COLMAP
                $id_ref['id_colmap'] = $id_colmap;
            } else {
                $collection_log = null;
                $id_ref = null;
            }

            LogErroORM::gerarLogUpdate($e->getMessage(), $update_log, $collection_log, $id_ref);

            return $result;
        }

        $result[1] = $this->obterPorId($id_entity, $objectResult);

        return $result;
    }

}

?>