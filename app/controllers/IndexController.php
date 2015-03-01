<?php

class IndexController extends TMetroUI{
    
    public function index() {
        $objPessoaLogic = new PessoaLogic();
        var_dump($objPessoaLogic->listar());
        exit();
        $this->TPageStart('index');
    }
    
}