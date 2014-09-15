<?php

/**
 * Description of Win8
 * @author igor
 */
class TMetroUI extends Controller {

    public $HTML;

    public function __construct() {

        parent::__construct();

        # Constantes
        define("TEMPLATE", __CLASS__ );
        define("PATH_DIR_TEMPLATE_URL", PATH_TEMPLATE_URL . TEMPLATE . "/");
        define("PATH_TEMPLATE_JS_URL", PATH_DIR_TEMPLATE_URL . "js/");
        define("PATH_TEMPLATE_CSS_URL", PATH_DIR_TEMPLATE_URL . "css/");
        define("PATH_TEMPLATE_IMAGE_URL", PATH_DIR_TEMPLATE_URL . "images/");      

        $this->HTML = new THtmlHelper();
    }

    public function init() {

        parent::init();

        # Definir icon padrãoo do sistema
        $this->HTML->setIcon(PATH_TEMPLATE_IMAGE_URL . "favicon.ico");

        # Definir nome da pagina
        $this->HTML->setTitle(strtoupper(NAME_SIS) . " - {$this->getController()}/{$this->getAction()}");

        $this->HTML->addJavaScript(PATH_TEMPLATE_JS_URL . 'docs.js', true);

        $this->HTML->addJavaScript(PATH_TEMPLATE_JS_URL . "load-metro.js", true); //4 a entrar
        $this->HTML->addJavaScript(PATH_TEMPLATE_JS_URL . "jquery/jquery.mousewheel.js", true); //3 a entrar
        $this->HTML->addJavaScript(PATH_TEMPLATE_JS_URL . "jquery/jquery.widget.min.js", true); //2 a entrar
        $this->HTML->addJavaScript(PATH_TEMPLATE_JS_URL . "jquery/jquery.min.js", true); //1 a entrar
        
        $this->HTML->addCss(PATH_TEMPLATE_CSS_URL . "docs.css", true); //3 entrar
        $this->HTML->addCss(PATH_TEMPLATE_CSS_URL . "metro-bootstrap-responsive.css", true); //2 entrar
        $this->HTML->addCss(PATH_TEMPLATE_CSS_URL . "metro-bootstrap.css", true); //1 entrar

        # Configurar Body
        $this->HTML->setBodyAttribute('class="metro"');

    }

    public function TPageStart($nome) {

        # Inicia o buffer
        ob_start();

        # Incluir view no tamplate 
        echo '<header class="bg-dark">';
        $this->viewCore('header');
        echo '</header>';

        # Inicio da pagina
        echo '<div class="container">';
        # Incluir view no tamplate
        $this->view($nome);
        echo '</div>';
        # Fim da pagina
        
        # inicio rodapé
        echo '<div>';
        $this->viewCore('footer');
        echo '</div>';
        # fim rodapé
        
        # Pegar view e aloca numa variavel
        $content = ob_get_clean();

        # Adiconar css
        if (file_exists(PATH_PUBLIC . "css/custom.css")) {
            $this->HTML->addCss(PATH_CSS_URL . "custom.css");
        }

        # Adicionar a view ao Body
        $this->HTML->setBodyContent($content);

        # Imprime o HTML
        echo $this->HTML->getHtml();
    }

}