<?php

/**
 * Template basico
 */
class TMyKing extends Controller {

    public $HTML;

    public function TMyKing() {

        parent::Controller();

        # Constantes
        define("TEMPLATE", 'TMyKing');
        define("PATH_TEMPLATE_URL", PATH_WEBFILES_URL . "templates/" . __CLASS__ . "/");
        define("PATH_TEMPLATE_JS_URL", PATH_TEMPLATE_URL . "js/");
        define("PATH_TEMPLATE_CSS_URL", PATH_TEMPLATE_URL . "css/");
        define("PATH_TEMPLATE_IMAGE_URL", PATH_TEMPLATE_URL . "images/");

        $this->HTML = new THtmlHelper();
    }

    public function init() {

        parent::init();
        
        # Definir icon padrão do sistema
        $favicon = (file_exists(PATH_PUBLIC . 'images/favicon.ico')) ? PATH_IMAGE_URL . 'favicon.ico' : PATH_TEMPLATE_IMAGE_URL . 'favicon.ico';
        $this->HTML->setIcon($favicon);
        unset($favicon);

        # Definir titulo padrão da pagina
        $this->HTML->setTitle("{$this->_controller}/{$this->_action}");

        # Adiconar css customizado
        if (file_exists(PATH_PUBLIC . "css/core/custom.css")) {
            $this->HTML->addCss(PATH_CSS_CORE_URL . "custom.css", true);
        }
    }

    public function TStart($nome) {

        # Inicia o buffer
        ob_start();

        # Incluir view no tamplate 
        $this->view($nome);

        # Pegar view e aloca numa variavel
        $content = ob_get_clean();

        # Adicionar a view ao Body
        $this->HTML->setBodyContent($content);

        # Imprime o HTML
        echo $this->HTML->getHtml();
    }

}

?>
