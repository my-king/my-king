<?php

/**
 * Cria a class de conexão da basee de dados
 *
 * @author igor
 */
class ConnORM {

    private static $instancia = null;
    private $storage;

    protected function __construct() {
        $this->storage = new ArrayObject();
    }

    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new ConnORM();
        }
        return self::$instancia;
    }

    public function __clone() {
        trigger_error('Clone não é permitido.', E_USER_ERROR);
    }

    public function get($key) {
        if ($this->storage->offsetExists($key)) {
            $conn = $this->storage->offsetGet($key);
            $this->isNotice($key, $conn);
            return $conn;
        }
    }

    public function set($nameDal) {
        if (!$this->storage->offsetExists($nameDal)) {
            $this->storage->offsetSet($nameDal, $this->getClassConn($nameDal));
            return true;
        } else {
            return false;
        }
    }

    public function unregister($key) {
        if ($this->storage->offsetExists($key)) {
            $this->storage->offsetUnset($key);
        }
    }

    private function getClassConn(&$nameDal) {
        /* Obter dados da conexão */
        $dados = $this->getDadosConexao($nameDal);
        if (!$dados) {
            return false;
        } else {
            /* Criar objeto com dados para conexão */
            $classConn = new stdClass();
            $classConn->type = $dados['type'];
            $classConn->server = $dados['server'];
            $classConn->port = (isset($dados['port'])) ? $dados['port'] : null;
            $classConn->database = $dados['database'];
            $classConn->user = $dados['user'];
            $classConn->password = $dados['password'];
            return $classConn;
        }
    }

    private function getDadosConexao(&$nameDal) {
        $ini = parse_ini_file('system/config/config.ini', true);
        return (isset($ini[$nameDal])) ? $ini[$nameDal] : false;
    }

}
