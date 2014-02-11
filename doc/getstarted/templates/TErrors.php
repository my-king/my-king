<?php

/**
 * Description of Win8
 * @author igor
 */
class TErrors extends Controller {

    public $HTML;
    public $NAVIGATOR;

    public function __construct() {

        parent::__construct();

        # Constantes
        define("TEMPLATE", __CLASS__ );
        define("PATH_DIR_TEMPLATE_URL", PATH_TEMPLATE_URL . TEMPLATE . "/");
        define("PATH_TEMPLATE_JS_URL", PATH_DIR_TEMPLATE_URL . "js/");
        define("PATH_TEMPLATE_CSS_URL", PATH_DIR_TEMPLATE_URL . "css/");
        define("PATH_TEMPLATE_IMAGE_URL", PATH_DIR_TEMPLATE_URL . "images/");

        # Iniciar Helpers
        $this->HTML = new THtmlHelper();
        $this->NAVIGATOR = TBrowserHelper::getBrowser();
    }

    public function init() {
        parent::init();
        $this->HTML->setIcon(PATH_IMAGE_URL . "favicon.ico");
    }

    public function TErro($nome) {

        # Inicia o bufferl
        ob_start();

        # Incluir view no tamplate 
        $this->view($nome);

        # Pegar view e alocar numa variavel
        $content = ob_get_clean();

        # Adicionar cor de fundo a pagina
        $this->HTML->setBodyAttribute("style='background:#F5F5F5'");

        # Adicionar a view ao Body
        $this->HTML->setBodyContent($content);

        # Imprimir o HTML
        echo $this->HTML->getHtml();
    }

}

?>
