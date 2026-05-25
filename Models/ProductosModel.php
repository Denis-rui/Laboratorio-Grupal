<?php

class ProductosModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "productos";
    }

    public function obtenerProductosConCategoria()
    {
        return $this->select("productos.id, productos.nombre, categorias.nombre AS categoria, productos.precio, productos.stock, productos.estado")
            ->join("categorias", "productos.categoria_id = categorias.id")
            ->orderBy("productos.id", "ASC")
            ->get();
    }

    public function obtenerProductoConCategoriaPorId($id)
    {
        return $this->select("productos.id, productos.nombre, categorias.nombre AS categoria, productos.precio, productos.stock, productos.estado")
            ->join("categorias", "productos.categoria_id = categorias.id")
            ->where(["productos.id" => (int)$id])
            ->first();
    }

    public function obtenerProductosActivos()
    {
        return $this->select("id, nombre, precio")
            ->where(["estado" => 1])
            ->orderBy("nombre", "ASC")
            ->get();
    }

    public function obtenerProductoActivoPorId($id)
    {
        return $this->select("id, nombre, precio")
            ->where(["id" => (int) $id, "estado" => 1])
            ->first();
    }
}
