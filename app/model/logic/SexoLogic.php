<?php

class SexoLogic extends LogicModel {

    public function __construct() {
        parent::__construct(new SexoDAO());
    }

}