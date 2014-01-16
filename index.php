<?php

/**
 * 
 *    Este arquivo é parte do Framework MyKing
 *    
 * 
 *    MyKing é um software livre; você pode redistribui-lo e/ou 
 *    
 *    modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 *    
 *    publicada pela Fundação do Software Livre (FSF); na versão 2 da 
 *    
 *    Licença.
 *    
 *    
 *    Este programa é distribuido na esperança que possa ser  util, 
 *    
 *    mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 *    
 *    MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
 *    
 *    Licença Pública Geral GNU para maiores detalhes.
 *    
 *    
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    
 *    junto com este programa, se não, escreva para a Fundação do Software
 *    
 *    Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


$config = parse_ini_file("system/config/config.ini", true);

foreach ($config["includes"] as $file) {
    require_once "system/" . $file . '.php';
}

// Definir as constante do sistema
Path::getInstancia();

// iniicia aplicação
$start = new System();
$start->run();

?>