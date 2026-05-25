<?php


class CategoriasModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "categorias";
    }

    public function obtenerCategorias()
    {
        return $this->orderBy("id", "ASC")->get();
    }

    public function obtenerCategoriaPorId($id)
    {
        return $this->where(["id" => (int) $id])->first();
    }
}
