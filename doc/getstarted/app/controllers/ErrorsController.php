<?php

/**
 * Controller direcionado visualização de erros
 * ocasionados durante execução do sistema
 * @author igor
 */
class ErrorsController extends TErrors {

    public function init() {
        parent::init();
        $this->HTML->addCss(PATH_WEBFILES_URL . "templates/TErrors/css/errors.css");
    }

    public function index() {
        $this->addDados('erro', 'Ocorreu um erro, isso é constrangedor!!!');
        $this->TErro("erro");
    }

    public function HTTP_401() {
        $this->addDados('erro', 'ERRO 401 - PAGINA NÃO EXISTE!!!');
        $this->TErro("erro");
    }

    public function HTTP_403() {
        $dados = array();
        $dados['erro'] = 'ERRO 403 - ACESSO NEGADO!!!';
        $this->TErro("erro");
    }

    public function HTTP_404() {
        $this->addDados('erro', 'ERRO 404 - PAGINA NÃO EXISTE!!!');
        $this->TErro("erro");
    }

    public function VIEW_404() {
        $this->addDados('erro', 'VIEW - NÃO EXISTE!!!');
        $this->TErro("erro");
    }

    public function database(){
        $this->addDados('erro', 'Servidor - Temporariamente indisponivel!!!');
        $this->TErro("erro");
    }


    public function browserSupported() {
        $browser = ($this->NAVIGATOR->browser == "MSIE") ? "INTERNET EXPLORER" : $this->NAVIGATOR->browser;      
        $this->addDados('erro', 'PARA PODER ACESSAR ESTE SISTEMA UTILIZE O FIREFOX!!!');      
        $this->addDados('browser', $browser);
        $this->addDados('browser_version', $this->NAVIGATOR->version);
        $this->addDados('browser_img', TImgHelper::img(PATH_IMAGE_URL . "Firefox.png"));
        $this->TErro("supported");
    }

}

?>
