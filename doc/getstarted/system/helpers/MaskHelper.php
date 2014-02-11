<?php

abstract class MaskHelper {

    public static function getMask($mascara, $string) {

        $string = str_replace(" ", "", $string);

        for ($i = 0; $i < strlen($string); $i++) {
            $mascara[strpos($mascara, "#")] = $string[$i];
        }

        return $mascara;
    }

    public static function cpf($cpf) {
        return MaskHelper::getMask("###.###.###-##", $cpf);
    }
    
    public static function numeroAjuste($numero) {
        return MaskHelper::getMask("####/####", $numero);
    }

}

?>
