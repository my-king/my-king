<?php

/**
 * Description of TBodyHelper
 * @author igor
 */
class TBodyHelper {
    
    private $attribute;
    private $content;


    /**
     * Adicionar o atributo ao body
     * @param type $attribute
     */
    public function setAttribute($attribute){
        $this->attribute = $attribute;
    }
    
    /**
     * Adicionar conteudo ao body
     * @param type $content
     */
    public function setContent($content){
        $this->content = $content;
    }

    /**
     * Adicionar conteudo adicional ao body
     * @param type $content
     */
    public function addContent($content){
        $this->content .= $content;
    }

    
    /**
     * Retorna o Body
     * @return string
     */
    public function getBody(){
        
        $body = "";
        
        if($this->attribute != null){
            $body .= "<body {$this->attribute} >";
        }else{
            $body .= "<body>";
        }
        
        if($this->content != null){
            $body .= $this->content;
        }
        
        $body .= "</body>";

        return $body;
    }
    
}

?>
