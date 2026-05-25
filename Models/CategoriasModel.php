<?php

class CategoriasModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "categorias";
    }
}
