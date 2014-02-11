<?php

class ValidateORM {

    public static function notNull($string) {

        if ($string === '' || $string === null) {
            return false;
        }

        return true;
    }

    public static function size($value, $size) {

        if (strlen($value) != $size) {
            return false;
        }

        return true;
    }

    /**
     * Valida cpf no formato 
     * @param type $cpf
     * @return boolean 
     */
    public static function cpf($cpf) {

        if (!preg_match("/^([0-9]){3}\.([0-9]){3}\.([0-9]){3}-([0-9]){2}$/", $cpf)) {
            return false;
        }

        $cpf = FormatORM::clearString($cpf);

        $cpf = str_pad(str_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * Valida se data é valida
     * Validate::data('dd/mm/yyyy')
     * @param type $data
     * @return boolean 
     */
    public static function data($data) {

        # verificar se data estar no formato correto
        if (!preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $data)) {
            return false;
        }

        $data = explode("/", $data);

        # checkdate(mes,dia,ano)
        $res = checkdate($data[1], $data[0], $data[2]);

        if (!$res) {
            return false;
        }

        return true;
    }

    /**
     * Valida se campo é um inteiro
     * @param type $inteiro
     * @return boolean 
     */
    public static function inteiro($inteiro) {
        
        if(preg_match("/^[0-9]+$/",$inteiro)){
            $inteiro = (int) $inteiro;
        }
        
        if (!is_int($inteiro)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Verifica se o e-mail é valido
     * @param type $email
     * @return boolean 
     */
    public static function email($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Verifica se tamanho da string é maior do quer o definido
     * @param type $value
     * @param type $size
     * @return boolean 
     */
    public static function maxSize($value, $size) {
        if (strlen($value) > $size) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se tamanho da string é menor do quer o definido
     * @param type $value
     * @param type $size
     * @return boolean 
     */
    public static function minSize($value, $size) {
        if (strlen($value) < $size) {
            return false;
        }
        return true;
    }

    /**
     * Valida telefone no seguinte formato: (##)####-####
     * @param string $telefone 
     */
    public static function telefone($telefone) {
        if (!preg_match("/^\([0-9]{2}\)[0-9]{4}-[0-9]{4}$/", $telefone)) {
            return false;
        }
        return true;
    }

    /**
     * Valida cep no seguinte formato: #####-###
     * @param string $cep 
     */
    public static function cep($cep) {
        if (!preg_match("/^[0-9]{5}-[0-9]{3}$/", $cep)) {
            return false;
        }
        return true;
    }

    /**
     * Valida cep no seguinte formato: ##.###.###/####-##
     * @param string $cnpj 
     */
    public static function cnpj($cnpj) {
        
        $cnpj = FormatORM::clearString($cnpj);
        
        if (!preg_match('|^(\d{2,3})\.?(\d{3})\.?(\d{3})\/?(\d{4})\-?(\d{2})$|', $cnpj, $matches)){
            return false;
        }
        
        array_shift($matches);

        $cnpj = implode('', $matches);
        if (strlen($cnpj) > 14)
            $cnpj = substr($cnpj, 1);

        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $calc1 = 5;
        $calc2 = 6;

        for ($i = 0; $i <= 12; $i++) {
            $calc1 = $calc1 < 2 ? 9 : $calc1;
            $calc2 = $calc2 < 2 ? 9 : $calc2;

            if ($i <= 11)
                $sum1 += $cnpj[$i] * $calc1;

            $sum2 += $cnpj[$i] * $calc2;
            $sum3 += $cnpj[$i];
            $calc1--;
            $calc2--;
        }

        $sum1 %= 11;
        $sum2 %= 11;

        return ($sum3 && $cnpj[12] == ($sum1 < 2 ? 0 : 11 - $sum1) && $cnpj[13] == ($sum2 < 2 ? 0 : 11 - $sum2)) ? true : false;
    }

    /**
     * Valida hora no seguinte formato: hh:mm
     * @param string $time 
     */
    public static function hora($time) {

        if (!preg_match("/^[0-9]{2}:[0-9]{2}$/", $time)) {
            return false;
        }

        list($hora, $minute) = explode(':', $time);

        if ($hora > -1 && $hora < 24 && $minute > -1 && $minute < 60) {
            return true;
        } else {
            return false;
        }
    }

}

?>
