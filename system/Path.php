<?php

/**
 * Description of Path
 *
 * @author igordahora
 */
class Path {

    private static $instancia = null;
    private $PATH;
    private $PATH_URL;

    //put your code here
    function __construct() {

        if (substr(getenv('DOCUMENT_ROOT'), strlen(getenv('DOCUMENT_ROOT')) - 1) == "/") {
            $path = getenv('DOCUMENT_ROOT');
        } else {
            $path = getenv('DOCUMENT_ROOT') . "/";
        }

        if (substr($_SERVER['HTTP_HOST'], strlen($_SERVER['HTTP_HOST']) - 1) == "/") {
            $path_url = 'http://' . $_SERVER['HTTP_HOST'];
        } else {
            $path_url = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        }

        $this->PATH = $path;
        $this->PATH_URL = $path_url;

        $this->defineConstantes();
    }

    public static function getInstancia() {
        if (self::$instancia == null) {
            self::$instancia = new Path();
        }

        return self::$instancia;
    }

    public function __clone() {
        trigger_error('Clone não permitido.', E_USER_ERROR);
    }

    private function defineConstantes() {

        $config = parse_ini_file("system/config/config.ini", true);

        //Application
        define('ROOT', $config['application']['root']);

        // Pastas padrões 
        defined('APP') or define('APP', ( isset($config['application']['app']) ? $config['application']['app'] : 'app/'));
        define('CONTROLLERS', APP.'controllers/');
        define('VIEWS', APP.'view/');
        define('WEBFILES', 'public/');

        if (ROOT == "/") {
            # PATH ABSOLUTOS
            $PATH = $this->PATH;
            # PATH URL
            $PATH_URL = $this->PATH_URL;
        } else {
            # PATH ABSOLUTOS
            $PATH = $this->PATH . ROOT;
            # PATH URL
            $PATH_URL = $this->PATH_URL . ROOT;
        }

        // PATH
        define('PATH', $PATH);
        define('PATH_SYSTEM', PATH . "system/");
        define('PATH_APP', PATH . APP);
        define('PATH_PUBLIC', PATH . "public/");
        define('PATH_LOG_ORM', PATH_SYSTEM . "orm/" .
                $config['log_orm']['root'] . "/" .
                $config['log_orm']['arquivo']
        );

        //PATH URL
        define('PATH_URL', $PATH_URL);
        define('PATH_WEBFILES_URL', PATH_URL . WEBFILES);
        define('PATH_TEMPLATE_URL', PATH_URL . "templates/");
        define('PATH_IMAGE_URL', PATH_WEBFILES_URL . "images/");
        define('PATH_JS_URL', PATH_WEBFILES_URL . "js/");
        define('PATH_JS_CORE_URL', PATH_JS_URL . "core/");
        define('PATH_CSS_URL', PATH_WEBFILES_URL . "css/");
        define('PATH_CSS_CORE_URL', PATH_CSS_URL . "core/");

        // VERSION
        define('VERSAO', $config['versionamento']['versao']);

    }

}

?>
