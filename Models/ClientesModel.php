<?php


class ClientesModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "clientes";
    }

    public function obtenerClientesActivos()
    {
        return $this->where(["estado" => 1])->orderBy("nombre", "ASC")->get();
    }
}
