<?php

/**
 * Template basico para requisi��es ajax
 * @author Igor da Hora <igordahora@gmail.com.br>
 */
class TRequest extends Controller {
    
    public function __construct() {
        parent::__construct();
        # Constantes
        define("TEMPLATE", __CLASS__ );
    }

}

?>
