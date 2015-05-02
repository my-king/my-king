<?php

class RegistryORM {

    private static $instancia = null;
    private $storage;

    protected function __construct() {
        $this->storage = new ArrayObject();
    }

    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new RegistryORM();
        }
        return self::$instancia;
    }

    public function __clone() {
        trigger_error('Clone não é permitido.', E_USER_ERROR);
    }

    private function isNotice($nameDal, &$conn) {
        if (!$conn) {
            $this->unregister($nameDal);
            RedirectorHelper::goToControllerAction("Errors", "database");
        }
    }

    public function get($nameDal) {
        if ($this->storage->offsetExists($nameDal)) {
            $conn = $this->storage->offsetGet($nameDal);
            $this->isNotice($nameDal, $conn);
            return $conn;
        }
    }

    private function tryConnect(&$drive, $tentativa = 0) {
        $conn = $drive->getConn();
        if ($tentativa === 2) {
            return $conn;
        } else {
            return ($conn !== false ) ? $conn : $this->tryConnect($drive, $tentativa + 1);
        }
    }

    public function set(&$nameDal) {
        if (!$this->storage->offsetExists($nameDal)) {
            $classConn = $this->getClassConn($nameDal);
            $drive = DriveORM::getDrive($classConn);
            $this->storage->offsetSet($nameDal, $this->tryConnect($drive));
            return true;
        } else {
            return false;
        }
    }

    public function unregister(&$nameDal) {
        if ($this->storage->offsetExists($nameDal)) {
            $this->storage->offsetUnset($nameDal);
        }
    }

    public function getClassConn(&$nameDal) {
        /* Obter dados da conexão */
        $dados = $this->getDadosConexao($nameDal);
        if (!$dados) {
            return false;
        } else {
            /* Criar objeto com dados para conexão */
            $classConn = new stdClass();
            $classConn->lib = $dados['lib'];
            $classConn->type = $dados['type'];
            $classConn->server = $dados['server'];
            $classConn->port = (isset($dados['port'])) ? $dados['port'] : null;
            $classConn->database = $dados['database'];
            $classConn->user = $dados['user'];
            $classConn->password = $dados['password'];
            return $classConn;
        }
    }

    private function getDadosConexao($nameDal) {
        $ini = parse_ini_file('system/config/dal.ini', true);
        return (isset($ini[$nameDal])) ? $ini[$nameDal] : false;
    }

}
