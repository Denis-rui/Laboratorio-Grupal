<?php

class ClientesModel extends Model{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "clientes";
    }
}