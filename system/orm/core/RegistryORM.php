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

    private function isNotice($key, &$conn) {
        if (!$conn) {
            $this->unregister($key);
            RedirectorHelper::goToControllerAction("Errors", "database");
        }
    }

    public function get($key) {
        if ($this->storage->offsetExists($key)) {
            $conn = $this->storage->offsetGet($key);
            $this->isNotice($key, $conn);
            return $conn;
        }
    }

    private function tryConnect(&$drive,$tentativa = 0) {
        $conn = $drive->getConn();
        if($tentativa === 2){
            return $conn;
        }else{
            return ($conn !== false ) ? $conn : $this->tryConnect($drive,$tentativa + 1);
        }
    }

    public function set($key, &$classConn) {
        if (!$this->storage->offsetExists($key)) {
            $drive = DriveORM::getDrive($classConn);
            $this->storage->offsetSet($key, $this->tryConnect($drive));
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

}