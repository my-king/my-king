<?php

   class ObjectHelper {

        
        /**
         * Obtem a diferença entre os arrays enviados a partir do atributo informado
         * ObjectHelper::obterDiferenca
         * @access public
         * @package system
         * @subpackage helpers
         * @param array[] $array1Objeto Array de Objetos 1
         * @param array[] $array2Objeto Array de Objetos 2
         * @return array[] $arrayReturn Array com os objetos que está em um e não contem no outro
         */        
        public static function obterDiferenca(array $array1Objeto, array $array2Objeto, $attr = "id"){
            
            $arrayReturn = array();
            
            if(count($array1Objeto)>count($array2Objeto)){
                $array1 = $array1Objeto;
                $array2 = $array2Objeto;
            } else {
                $array2 = $array1Objeto;
                $array1 = $array2Objeto;
            }
            
            foreach($array1 as $obj1){
                $metodo = new ReflectionMethod(get_class($obj1),"get".ucfirst($attr));
                $flag = false;
                foreach($array2 as $obj2){
                    if($metodo->invoke($obj1) == $metodo->invoke($obj2)){
                        $flag = true;
                        break;
                        echo $metodo->invoke($obj2)."<br>";
                    }
                }
                
                if(!$flag)
                    $arrayReturn[] = $obj1;
                
            }
            
            return $arrayReturn;
        }
        
    }
?>