<?php

class ProductosModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "productos";
    }

    public function listarConCategoria()
    {
        return $this->select("productos.id, productos.nombre, categorias.nombre AS categoria, productos.precio, productos.stock, productos.estado")
            ->join("categorias", "productos.categoria_id = categorias.id")
            ->orderBy("productos.id", "ASC")
            ->get();
    }

    public function verConCategoria($id)
    {
        return $this->select("productos.id, productos.nombre, categorias.nombre AS categoria, productos.precio, productos.stock, productos.estado")
            ->join("categorias", "productos.categoria_id = categorias.id")
            ->where(["productos.id" => (int)$id])
            ->first();
    }

    public function obtenerCategorias()
    {
        $sql = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
        $stmt = $this->conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
