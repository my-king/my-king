<?php

class ReflectionORM extends ReflectionClass {

    private $nameClass;
    private $atributos;
    private $propAtributos = array();
    private $classAnnotations = array();
    private $propAnnotations = array();
    private $regex1 = "@[\w]+[ ]{0,1}=[ ]{0,1}[\w=, \.\-<>:]+";
    private $regex2 = "@[\w]+[ ]{0,1}\([\w=, \.\-<>:]+\)";
    private $regex3 = "[\w]+[ ]{0,1}=[ ]{0,1}[\w]+";
    private $regex4 = "@[\w]+";

    public function __construct($class) {

        parent::__construct($class);

        $this->nameClass = $class;
        $this->extractDocAnnotations();
        $this->extractAtributos();
        $this->extractPropAtributos();
    }

    public function getObjClass() {
        return new $this->nameClass();
    }

    public function getClass() {
        return $this->nameClass;
    }

    public function getColmap() {

        $colmaps = array();

        foreach ($this->getAtributos() as $atributo) {
            $colmap = $this->getPropAnnotations($atributo, "@Colmap");
            if ($colmap !== false) {
                $colmaps[] = $colmap;
            }
        }

        return $colmaps;
    }

    public function getClassAnnotations($annotation = null) {

        if ($annotation != null) {
            $annotation = (isset($this->classAnnotations[$annotation])) ? $this->classAnnotations[$annotation] : "";
            return $annotation;
        }

        return $this->classAnnotations;
    }

    public function getPropAnnotations($atributo = null, $annotation = null) {

        if ($atributo != null) {

            if ($annotation != null) {

                if (!isset($this->propAnnotations[$atributo][$annotation])) {
                    return false;
                }

                return $this->propAnnotations[$atributo][$annotation];
            }

            return $this->propAnnotations[$atributo];
        }

        return $this->propAnnotations;
    }

    public function getAtributo($colmap = null) {

        foreach ($this->atributos as $atributo) {
            $coluna = $this->getPropAnnotations($atributo, "@Colmap");
            if ($colmap === $coluna) {
                return $atributo;
            }
        }

        return false;
    }

    public function getPropAtributos() {
        return $this->propAtributos;
    }

    public function getAtributos() {
        return $this->atributos;
    }

    public function setAtributos($atributos) {
        $this->atributos[] = $atributos;
    }

    public function isPropAnnotation($atributo, $annotation) {
        if (isset($this->propAnnotations[$atributo][$annotation])) {
            return true;
        } else {
            return false;
        }
    }

    private function extractPropAtributos() {

        $mapAtributo = array();

        foreach ($this->getAtributos() as $atributo) {

            $colmap = $this->getPropAnnotations($atributo, '@Colmap');
            $relationship = $this->getPropAnnotations($atributo, '@Relationship');

            # se for uma colmap          
            if ($colmap !== false) {

                # Adicionar Serial ao mapeamento
                if ($atributo === 'id') {
                    $serial = $this->getPropAnnotations($atributo, '@Serial');
                    if ($serial !== false) {
                        $mapAtributo[$atributo]['Serial'] = true;
                    }
                    unset($serial);
                }

                # Adicionar Colmap ao mapeamento
                $mapAtributo[$atributo]['Colmap'] = $colmap;

                # adicionar Relacionamento OneToOne ao mapeamento
                if ($relationship !== false) {
                    if ($relationship->type == 'OneToOne') {
                        $mapAtributo[$atributo]['OneToOne']['objeto'] = $relationship->objeto;
                    }
                }

                # Adicionar persistencia ao mapeamento
                $persistence = $this->getPropAnnotations($atributo, "@Persistence");
                if ($persistence !== false) {
                    $mapAtributo[$atributo]['Persistence'] = $persistence;
                }
                # limpar memoria
                unset($persistence);

                # Adicionar campo mask ao mapeamento
                $mask = $this->getPropAnnotations($atributo, '@Mask');
                if ($mask !== false) {
                    $mapAtributo[$atributo]['Mask'] = $mask;
                }
                # limpar memoria
                unset($mask);
            } else {
                if ($relationship !== false) {

                    if ($relationship->type === 'OneToMany') {

                        $mapAtributo[$atributo]['OneToMany']['objeto'] = $relationship->objeto;

                        if (isset($relationship->coluna)) {
                            $mapAtributo[$atributo]['OneToMany']['coluna'] = $relationship->coluna;
                        }
                    } elseif ($relationship->type === 'ManyToMany') {

                        $mapAtributo[$atributo]['ManyToMany']['objeto'] = $relationship->objeto;

                        if (isset($relationship->schema)) {
                            $mapAtributo[$atributo]['ManyToMany']['schema'] = $relationship->schema;
                        }

                        if (isset($relationship->coluna)) {
                            $mapAtributo[$atributo]['ManyToMany']['coluna'] = $relationship->coluna;
                        }

                        $mapAtributo[$atributo]['ManyToMany']['table'] = $relationship->table;
                    }
                }
            }

            unset($colmap);
            unset($relationship);
        }

        $this->propAtributos = $mapAtributo;
        unset($mapAtributo);
    }

    private function extractAtributos() {
        $atributos = array_keys($this->getDefaultProperties());
        $this->atributos = $atributos;
    }

    private function extractDocAnnotations() {
        preg_match_all(
                "/{$this->regex1}|{$this->regex2}/", $this->getDocComment(), $this->classAnnotations
        );

        sort($this->classAnnotations);

        $ar = array();
        $matches = array();

        foreach ($this->classAnnotations[0] as $subject) {
            preg_match('/@[\w]+/', $subject, $matches, PREG_OFFSET_CAPTURE);
            $temp = str_replace($matches[0][0], '', $subject);
            $value = $this->tratarValue($temp);
            $ar[$matches[0][0]] = $value;
        }

        if (count($ar) > 0) {
            $this->classAnnotations = $ar;
        }

        $properties = $this->getProperties();

        foreach ($properties as $prop) {
            $this->extractPropAnnotations($prop->name);
        }
    }

    private function extractPropAnnotations($property) {
        $ref = new ReflectionProperty($this->nameClass, $property);

        $this->propAnnotations[$property] = array();

        preg_match_all(
                "/{$this->regex1}|{$this->regex2}|{$this->regex4}/", $ref->getDocComment(), $this->propAnnotations[$property]
        );

        sort($this->propAnnotations[$property][0]);


        $ar = array();
        $matches = array();
        foreach ($this->propAnnotations[$property][0] as $subject) {

            preg_match('/@[\w]+/', $subject, $matches, PREG_OFFSET_CAPTURE);

            if ($matches[0][0] == $subject) {
                $ar[$matches[0][0]] = true;
            } else {
                $ar[$matches[0][0]] = $this->getAnnotation($subject);
            }
        }

        if (count($ar) > 0) {
            $this->propAnnotations[$property] = $ar;
        }
    }

    private function getAnnotation($annotation) {
        $r = array();
        if (preg_match("/{$this->regex1}/", $annotation)) {
            $r = $this->parseRegex1($annotation);
        } else if (preg_match("/{$this->regex2}|{$this->regex3}/", $annotation)) {
            $r = $this->parseRegex2($annotation);
        }

        if (is_array($r)) {
            $r = (object) $r;
        }

        return $r;
    }

    private function parseRegex1($annotation) {
        return preg_replace(
                '/@[\w]+=/', '', preg_replace('/[ ]{0,1}=[ ]{0,1}/', '=', $annotation)
        );
    }

    private function parseRegex2($annotation) {

        if (preg_match('/@[\w]+/', $annotation)) {

            $annotation = preg_replace(
                    '/@[\w]+/', '', preg_replace('/[ ]{0,1}=[ ]{0,1}\(/', '=', $annotation)
            );

            $annotation = preg_replace(
                    '/^\(/', '', preg_replace('/\)$/', '', trim($annotation))
            );

            preg_match_all(
                    "/{$this->regex3}/", trim($annotation), $annotation
            );


            $return = array();
            foreach ($annotation[0] as $value) {

                preg_match_all(
                        "/[\w]+/", trim($value), $annotation
                );

                $return[$annotation[0][0]] = $annotation[0][1];
            }
            return $return;
        }
    }

    private function tratarValue($value) {
        $matches = array();
        preg_match_all(
                "/[\w]+/", $value, $matches
        );
        return $matches[0][0];
    }

}

?>
