<?php

/**
 * Description of FormatORM
 * Retorno do banco de dados
 * @author igor
 */
class PersistenceORM {

    public static $erro = null;
    public static $value = "";

    /**
     * Retorna o erro ou campo formatado
     * @return type 
     */
    public static function getErro() {

        $result = array();

        if (PersistenceORM::$erro !== null) {
            $result[0] = false;
            $result[1] = PersistenceORM::$erro;
        } else {
            $result[0] = true;
            $result[1] = PersistenceORM::$value;
        }

        # Zera variaves staticas
        PersistenceORM::$erro = null;
        PersistenceORM::$value = "";

        return $result;
    }

    /**
     * Faz a persistencia de campos do tipo texto
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function texto($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }

            # Se existe $persistence->MinSize
            if (isset($persistence->MinSize)) {
                if (!ValidateORM::minSize($value, $persistence->MinSize)) {
                    PersistenceORM::$erro[] = "Minimo de {$persistence->MinSize} caracters!!!";
                }
            }

            # Se existe $persistence->MaxSize
            if (isset($persistence->MaxSize)) {
                if (!ValidateORM::maxSize($value, $persistence->MaxSize)) {
                    PersistenceORM::$erro[] = "Maximo de {$persistence->MaxSize} caracters!!!";
                }
            }
        }

        return PersistenceORM::getErro();
    }

    /**
     * Faz a persistencia de campos do tipo inteiro
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function inteiro($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }

            # Se existe $persistence->MinSize
            if (isset($persistence->MinSize)) {
                if (!ValidateORM::minSize($value, $persistence->MinSize)) {
                    PersistenceORM::$erro[] = "Minimo de {$persistence->MinSize} caracters!!!";
                }
            }

            # Se existe $persistence->MaxSize
            if (isset($persistence->MaxSize)) {
                if (!ValidateORM::maxSize($value, $persistence->MaxSize)) {
                    PersistenceORM::$erro[] = "Maximo de {$persistence->MaxSize} caracters!!!";
                }
            }

            if (!ValidateORM::inteiro($value)) {
                PersistenceORM::$erro[] = "O campo não é um inteiro!!!";
            } else {
                PersistenceORM::$value = (int) $value;
            }
        }

        return PersistenceORM::getErro();
    }

    public static function numeroAjuste($persistence, $value) {
        
        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Limpa a string
            $value = FormatORM::clearString($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }

        return PersistenceORM::getErro();
        
    }
    
    public static function codigoFonteRecurso($persistence, $value) {
        
        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Limpa a string
            $value = FormatORM::clearString($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }

        return PersistenceORM::getErro();
        
    }    

    /**
     * Faz a persistencia de campos do tipo monetario
     * @tutorial PersistenceORM::monetario($ObjPersistence, "999.999.999.999,99");
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function monetario($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Limpa a string
            $value = FormatORM::monetario($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->MaxSize
            if (isset($persistence->MaxSize)) {
                if (!ValidateORM::maxSize($value, $persistence->MaxSize)) {
                    PersistenceORM::$erro[] = "Maximo de {$persistence->MaxSize} caracters!!!";
                }
            }
            
        }


        return PersistenceORM::getErro();
    }
    /**
     * Faz a persistencia de campos do tipo monetario
     * @tutorial PersistenceORM::monetario($ObjPersistence, "999.999.999.999,99");
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function decimal($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Limpa a string
            $value = FormatORM::decimal($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->MaxSize
            if (isset($persistence->MaxSize)) {
                if (!ValidateORM::maxSize($value, $persistence->MaxSize)) {
                    PersistenceORM::$erro[] = "Maximo de {$persistence->MaxSize} caracters!!!";
                }
            }
            
        }


        return PersistenceORM::getErro();
    }
    /**
     * Faz a persistencia de campos do tipo cpf
     * @tutorial PersistenceORM::cpf($ObjPersistence, "###.###.###-##");
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function cpf($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Validar CPF
            if (!ValidateORM::cpf($value)) {
                PersistenceORM::$erro[] = "CPF invalido!!!";
            }

            # Limpa a string
            $value = FormatORM::clearString($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }


        return PersistenceORM::getErro();
    }

    /**
     * Faz a persistencia de campos do tipo cnpj
     * @tutorial PersistenceORM::cnpj($ObjPersistence, "##.###.###/####-##");
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function cnpj($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Validar CPF
            if (!ValidateORM::cnpj($value)) {
                PersistenceORM::$erro[] = "CNPJ invalido!!!";
            }

            # Limpa a string
            $value = FormatORM::clearString($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }


        return PersistenceORM::getErro();
    }    
    
    /**
     * Faz a persistencia de campos do tipo data
     * @tutorial PersistenceORM::data($ObjPersistence, "dd/mm/yyyy");
     * @param type $persistence
     * @param type $value
     * @return type 
     */
    public static function data($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            #valida data
            if (!ValidateORM::data($value)) {
                PersistenceORM::$erro[] = "Data invalida!!!";
            }

            # formatar data
            $value = FormatORM::dataNormalToInversa($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }

        return PersistenceORM::getErro();
    }

    /**
     * Faz a persistencia de campos do tipo hora
     * @tutorial PersistenceORM::hora($ObjPersistence, "hh:mm");
     * @param type $persistence
     * @param type $value
     * @return type 
     */
    public static function hora($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            #valida data
            if (!ValidateORM::hora($value)) {
                PersistenceORM::$erro[] = "Hora invalida!!!";
            }

            # formatar data
            $value = FormatORM::clearString($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }

        return PersistenceORM::getErro();
    }    
    
    
    /**
     * Faz a persistencia de campos do tipo telefone
     * @tutorial PersistenceORM::telefone($ObjPersistence, "(##)####-####");
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function telefone($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            if (!ValidateORM::telefone($value)) {
                PersistenceORM::$erro[] = "Telefone invalido!!!";
            }

            # Limpa a string
            $value = FormatORM::clearString($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }

        return PersistenceORM::getErro();
    }

    /**
     * Faz a persistencia de campos do tipo agencia
     * @tutorial PersistenceORM::agencia($ObjPersistence, "####-#");
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function agencia($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Limpa a string
            $value = FormatORM::clearString($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }

        return PersistenceORM::getErro();
    }
    
    /**
     * Faz a persistencia de campos do tipo conta
     * @tutorial PersistenceORM::conta($ObjPersistence, "###########-#");
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function conta($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Limpa a string
            $value = FormatORM::clearString($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }

        return PersistenceORM::getErro();
    }
    
    /**
     * Faz a persistencia de campos do tipo cep
     * @tutorial PersistenceORM::cep($ObjPersistence, "#####-###");
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function cep($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

//            if (!ValidateORM::cep($value)) {
//                PersistenceORM::$erro[] = "Cep invalido!!!";
//            }

            # Limpa a string
            $value = FormatORM::clearString($value);

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }
        }

        return PersistenceORM::getErro();
    }

    /**
     * Faz a persistencia de campos do tipo senha
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function senha($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {
            
           
            if(!isset($value{31})){
                
                # Se existe $persistence->MinSize
                if (isset($persistence->MinSize)) {
                    if (!ValidateORM::minSize($value, $persistence->MinSize)) {
                        PersistenceORM::$erro[] = "Minimo de {$persistence->MinSize} caracters!!!";
                    }
                }

                # Se existe $persistence->MaxSize
                if (isset($persistence->MaxSize)) {
                    if (!ValidateORM::maxSize($value, $persistence->MaxSize)) {
                        PersistenceORM::$erro[] = "Maximo de {$persistence->MaxSize} caracters!!!";
                    }
                }                
                
                # criptografar
                $value = md5($value);                
            }
            
            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }

        }

        return PersistenceORM::getErro();
    }    
    
    
    /**
     * Faz a persistencia de campos do tipo email
     * @param type $persistence
     * @param type $value
     * @return array
     */
    public static function email($persistence, $value) {

        # Se existe $persistence->NotNull
        if (isset($persistence->NotNull)) {
            if (!ValidateORM::notNull($value)) {
                PersistenceORM::$erro[] = "Campo não pode ser nulo!!!";
            }
        }

        # Campo não é nulo
        if (ValidateORM::notNull($value)) {

            # Atribuir string a variavel static $value
            PersistenceORM::$value = $value;

            # Se existe $persistence->size
            if (isset($persistence->size)) {
                if (!ValidateORM::size($value, $persistence->size)) {
                    PersistenceORM::$erro[] = "Total de caracteres tem que ser igual a {$persistence->size}!!!";
                }
            }

            # Se existe $persistence->MinSize
            if (isset($persistence->MinSize)) {
                if (!ValidateORM::minSize($value, $persistence->MinSize)) {
                    PersistenceORM::$erro[] = "Minimo de {$persistence->MinSize} caracters!!!";
                }
            }

            # Se existe $persistence->MaxSize
            if (isset($persistence->MaxSize)) {
                if (!ValidateORM::maxSize($value, $persistence->MaxSize)) {
                    PersistenceORM::$erro[] = "Maximo de {$persistence->MaxSize} caracters!!!";
                }
            }
            
            if(!ValidateORM::email($value)){
                PersistenceORM::$erro[] = "Email invalido!!!";
            }
            
        }

        return PersistenceORM::getErro();
    }    
    
}

?>
