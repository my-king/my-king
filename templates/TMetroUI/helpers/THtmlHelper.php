<?php

/**
 * Description of THtmlHelper
 * Class para definir a estrutura padrão html a ser adotada pelo sistema
 * @author igor
 */
class THtmlHelper {

    private $head;
    private $body;

    public function __construct() {
        $this->head = new THeadHelper();
        $this->body = new TBodyHelper();
    }

    /**
     * Limpar js, css e metatados do head
     */
    public function headClear() {
        $this->head->clear();
    }

    /**
     * Define o doctype do html
     * @return string
     */
    private function doctype() {
        return "<!DOCTYPE html>";
    }

    /**
     * Define a tag de inicialização do html
     * @return string
     */
    private function start() {
        return "<html>";
    }

    /**
     * Define a tag de finalização do html
     * @return string
     */
    private function close() {
        return "</html>";
    }

    /**
     * Adicionar title ao Objeto THeadHelper
     * @param type $title
     */
    public function setTitle($title) {
        $this->head->setTitle($title);
    }

    /**
     * Definir o icone ao Objeto THeadHelper THeadHelper
     * @param type $urlIcon
     */
    public function setIcon($location) {
        $this->head->setIcon($location);
    }

    /**
     * Adiciona meta tag keywords ao Objeto THeadHelper - no caso de mais de uma palavra colocar virgula
     * @param type $keywords
     */
    public function addMetaKeyWords($keywords) {
        $this->head->addMetaKeyWords($keywords);
    }

    /**
     * Adiciona meta tag description ao Objeto THeadHelper
     * @param type $description
     */
    public function addMetaDescription($description) {
        $this->head->addMetaDescription($description);
    }

    /**
     * Adiciona meta tag author ao Objeto THeadHelper 
     * @param type $author
     */
    public function addMetaAuthor($author) {
        $this->head->addMetaAuthor($author);
    }

    /**
     * Adiciona meta tag language ao Objeto THeadHelper
     * @param type $language
     */
    public function addMetaLanguage($language) {
        $this->head->addMetaLanguage($language);
    }

    /**
     * Adicionar CSS ao Objeto THeadHelper
     * @param type $url
     * @param type $prioridade
     */
    public function addCss($location, $priority = false) {
        $this->head->addCss($location, $priority);
    }

    /**
     * Adicionar JavaScrip ao Objeto THeadHelper
     * @param type $localizacao
     * @param type $prioridade
     */
    public function addJavaScript($location, $priority = false) {
        $this->head->addJavaScript($location, $priority);
    }

    /**
     * Adicionar Conteudo ao Objeto THeadHelper
     * @param type $content
     * @param type $prioridade
     */
    public function addHeadContent($content, $priority = false) {
        $this->head->addContent($content, $priority);
    }

    /**
     * Adicionar attributo ao body
     * @param type $attribute
     */
    public function setBodyAttribute($attribute) {
        $this->body->setAttribute($attribute);
    }

    /**
     * Adicionar conteudo ao body
     * @param type $attribute
     */
    public function setBodyContent($content) {
        $this->body->setContent($content);
    }

    /**
     * Adicionar conteudo ao body
     * @param type $attribute
     */
    public function addBodyContent($content) {
        $this->body->addContent($content);
    }

    /**
     * Retorna html formado
     */
    public function getHtml() {

        # seta o doctype
        $html = $this->doctype();

        # Inicializa tag Html
        $html .= $this->start();

        # Pegar o head
        $html .= $this->head->getHead();

        # Pega o body
        $html .= $this->body->getBody();

        # Finaliza o html
        $html .= $this->close();

        # Retorna o html
        return $html;
    }

}

?>
