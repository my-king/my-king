<?php

class IndexController extends TMetroUI{
    
    public function index() {
        $objSexo = new SexoLogic();
        var_dump($objSexo->listar());
        $objPessoaLogic = new PessoaLogic();
        var_dump($objPessoaLogic->listar(null,null,true));
        exit();
        $this->TPageStart('index');
    }
    
}