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
        return $this->select("productos.id, productos.categoria_id, productos.nombre, categorias.nombre AS categoria, productos.precio, productos.stock, productos.estado")
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

    public function obtenerFiltrosPorCategoria($categoriaId)
    {
        $sql = "SELECT DISTINCT
                a.nombre AS atributo,
                vp.valor
            FROM productos p
            INNER JOIN valores_productos vp
                ON p.id = vp.producto_id
            INNER JOIN atributos a
                ON vp.atributo_id = a.id
            WHERE p.categoria_id = :categoria_id
            ORDER BY a.nombre, vp.valor";

        $stmt = $this->conectar()->prepare($sql);
        $stmt->bindValue(':categoria_id', (int)$categoriaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
