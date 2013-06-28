<?php

$config = parse_ini_file("system/config/config.ini", true);

foreach ($config["includes"] as $file) {
    require_once "system/".$file.'.php';
}

// Definir as constante do sistema
Path::getInstancia();

// iniicia aplicaчуo
$start = new System();
$start->run();

?>