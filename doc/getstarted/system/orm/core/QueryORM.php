<?php

/**
 * Description of QueryORM
 * Criar query apartir da entidade passada
 * @author igorsantos
 */
class QueryORM {

    private $entidade;
    private $colunas;
    private $alias = array();
    private $query;

    public function __construct($entidade) {
        $this->entidade = $entidade;
        $this->createAlias($entidade);
    }

    public function createAlias($entidade) {
        $this->alias[$entidade] = strtolower($entidade);
    }

    public function reflection($class) {
        return new ReflectionORM($class);
    }

    public function getQuery() {

        $query = "";
        foreach ($this->query as $k => $array) {
            if ($k == 0) {
                if (isset($array['select'])) {
                    $query .= "SELECT " . implode(",", $this->colunas) . " FROM ".$array['select'];
                }
            } else {
                
            }
        }
        var_dump($query);
    }

    public function select($atributos) {
        $objReflection = $this->reflection($this->entidade);
        
        $schema = $objReflection->getClassAnnotations("@Schema");
        if (($schema !== false)) {
            $tabela = $schema . "." . $objReflection->getClassAnnotations("@Table") . " AS " . $this->alias[$this->entidade];
        } else {
            $tabela = $objReflection->getClassAnnotations("@Table") . " AS " . $this->alias[$this->entidade];
        }

        $this->query[]['select'] = $tabela;
        $this->addColunas($atributos);
    }

    private function addColunas($atributos, $entidade = null) {

        $atributos = explode(",", $atributos);

        if ($entidade !== null) {

            $objReflection = $this->reflection($entidade);
            $this->createAlias($entidade);

            foreach ($atributos as $atributo) {
                $colmap = $objReflection->getPropAnnotations($atributo, "@Colmap");
                if ($colmap !== false) {
                    $this->colunas[] = $this->alias[$entidade] . "." . $colmap;
                }
            }
        } else {

            $objReflection = $this->reflection($this->entidade);
            foreach ($atributos as $atributo) {
                $colmap = $objReflection->getPropAnnotations($atributo, "@Colmap");
                if ($colmap !== false) {
                    $this->colunas[] = $this->alias[$this->entidade] . "." . $colmap;
                }
            }
        }
    }

}

?>
