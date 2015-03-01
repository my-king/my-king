<?php

class PessoaLogic extends LogicModel {

    public function __construct() {
        parent::__construct(new PessoaDAO());
    }

}