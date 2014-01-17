<?php

/**
 * Class responsavel por montar o head da pagina
 *
 * @author igor
 */
class THeadHelper {

    private $title;
    private $icon;
    private $metaTag;
    private $css;
    private $javaScript;
    private $content;

    public function __construct() {
        $this->metaTag = array();
        $this->css = array();
        $this->javaScript = array();
        $this->content = array();
    }

    public function clear() {
        $this->metaTag = array();
        $this->css = array();
        $this->javaScript = array();
        $this->content = array();
    }

    /**
     * Definir titulo da pagina
     * @param type $title
     */
    public function setTitle($title) {
        $this->title = "<title>{$title}</title>\n";
    }

    /**
     * Definir o icone da pagina
     * @param type $location
     */
    public function setIcon($location) {
        $this->icon = "<link rel='shortcut icon' href='{$location}' type='image/x-icon' />\n";
    }

    /**
     * Verifia se meta já existe se não existir adicionar a metatag
     * @param type $meta
     */
    private function setMetaTag($meta) {
        if (!in_array($meta, $this->metaTag)) {
            $this->metaTag[] = $meta;
        }
    }

    /**
     * Adiciona meta tag keywords ao head - no caso de mais de uma palavra colocar virgula
     * @param type $keywords
     */
    public function addMetaKeyWords($keywords) {
        $this->setMetaTag("<META NAME='Keywords' CONTENT='{$keywords}'>\n");
    }

    /**
     * Adiciona meta tag description ao head
     * @param type $description
     */
    public function addMetaDescription($description) {
        $this->setMetaTag("<META NAME='Description' CONTENT='{$description}'>\n");
    }

    /**
     * Adiciona meta tag author ao head 
     * @param type $author
     */
    public function addMetaAuthor($author) {
        $this->setMetaTag("<META NAME='Author' CONTENT='{$author}'>\n");
    }

    /**
     * Adiciona meta tag language ao head
     * @param type $language
     */
    public function addMetaLanguage($language) {
        $this->setMetaTag("<meta http-equiv='content-language' content='{$language}'>\n");
    }

    /**
     * Adicionar CSS
     * @param type $location
     * @param type $priority
     */
    public function addCss($location, $priority = false) {

        $flag = ($priority == true) ? true : $priority;
        $css = "<link rel='stylesheet' type='text/css' href='{$location}' />\n";

        if ($flag) {
            if (!in_array($css, $this->css)) {
                array_unshift($this->css, $css);
            }
        } elseif (!$flag) {
            if (!in_array($css, $this->css)) {
                $this->css[] = $css;
            }
        }
    }

    /**
     * Adicionar JavaScrip
     * @param type $location
     * @param type $priority
     */
    public function addJavaScript($location, $priority = false) {

        # define a priority
        $flag = ($priority == true) ? true : $priority;
        $js = "<script language='javascript' src='{$location}'></script>\n";

        if ($flag) {
            if (!in_array($js, $this->javaScript)) {
                array_unshift($this->javaScript, $js);
            }
        } elseif (!$flag) {
            if (!in_array($js, $this->javaScript)) {
                $this->javaScript[] = $js;
            }
        }
    }
    /**
     * Adicionar Conteudo ao head
     * @param type $content
     * @param type $priority
     */
    public function addContent($content, $priority = false) {

        # define a priority
        $flag = ($priority == true) ? true : $priority;
        if ($flag) {
            if (!in_array($content, $this->content)) {
                array_unshift($this->content, $content);
            }
        } elseif (!$flag) {
            if (!in_array($content, $this->content)) {
                $this->content[] = $content;
            }
        }
    }

    public function getHead() {

        $head = "<head>\n";

        if ($this->title != null) {
            $head .= $this->title;
        }

        if ($this->icon != null) {
            $head .= $this->icon;
        }

        if (count($this->metaTag) != 0) {
            foreach ($this->metaTag as $metaTag) {
                $head .= $metaTag;
            }
        }

        if (count($this->css) != 0) {
            foreach ($this->css as $css) {
                $head .= $css;
            }
        }

        if (count($this->javaScript) != 0) {
            foreach ($this->javaScript as $js) {
                $head .= $js;
            }
        }

        if (count($this->content) != 0) {
            foreach ($this->content as $content) {
                $head .= $content;
            }
        }

        $head .= "</head>\n";
        
        return $head;
    }

}

?>
