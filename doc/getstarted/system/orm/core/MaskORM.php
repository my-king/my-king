<?php

abstract class MaskORM {
    
    public static function getMask($mascara, $string) {
        
        $string = str_replace(" ", "", $string);
        
        for ($i = 0; $i < strlen($string); $i++) {
            $mascara[strpos($mascara, "#")] = $string[$i];
        }
        
        return $mascara;
    }
    
    public static function cpf($cpf){
        
        if($cpf !== null){
            return MaskORM::getMask('###.###.###-##', $cpf);
        }
        
        return null;
    }
    
    public static function tituloEleitor($tituloEleitor){
        
        if($tituloEleitor !== null){
            return MaskORM::getMask('#########/##', $tituloEleitor);
        }
        
        return null;
    }

    public static function cnpj($cnpj){
        
        if($cnpj !== null){
            return MaskORM::getMask('##.###.###/####-##', $cnpj);
        }
        
        return null;
    }
    
    public static function data($data) {
        
        if($data !== null){
            $data_unformat = substr($data, 6, 2) . substr($data, 4, 2) . substr($data, 0, 4);
            $dia = substr($data_unformat, 0, 2);
            $mes = substr($data_unformat, 2, 2);
            $ano = substr($data_unformat, 4, 4);
            $data = $dia . $mes . $ano;
            return MaskORM::getMask('##/##/####', $data);
        }
        
        return null;

    }
    
    public static function numeroAjuste($numero){
        
        if($numero !== null){
            return MaskORM::getMask('####/####', $numero);
        }
        
        return null;
    }
    
    public static function telefone($telefone){
        
        if($telefone !== null){
            return MaskORM::getMask('(##)####-####', $telefone);
        }
        
        return null;
    }
    
    public static function cep($cep){
        
        if($cep !== null){
            return MaskORM::getMask('#####-###', $cep);
        }
        
        return null;
    }
    
    public static function agencia($agencia){
        
        if($agencia !== null){
            return MaskORM::getMask('####-#', $agencia);
        }
        
        return null;
    }

    public static function conta($conta){
        
        if($conta !== null){
            return MaskORM::getMask('###########-#', $conta);
        }
        
        return null;
    }

    public static function codigoFonteRecurso($codigoFonteRecurso){
        
        if($codigoFonteRecurso !== null){
            return MaskORM::getMask('#.###.######', $codigoFonteRecurso);
        }
        
        return null;
    }

    public static function hora($hora){
        
        if($hora !== null){
            return MaskORM::getMask('##:##', $hora);
        }
        
        return null;
    }
    
    public static function datatime($datatime){
        
        if($datatime !== null){
            return date("d-m-Y H:i:s", $datatime);
        }
        
        return null;
    }
    
     public static function monetario($valor){
        
        if($valor !== null){
            return number_format($valor, 2, ',', '.');
        }
        
        return null;
    }
    
}

?>
