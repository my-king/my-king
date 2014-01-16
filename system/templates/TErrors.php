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
        $this->HTML = new THtmlHelper();
        $this->NAVIGATOR = TBrowserHelper::getBrowser();
        define('PATH_TEMPLATE_URL', PATH_WEBFILES_URL . "templates/" . __CLASS__ . "/");
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
