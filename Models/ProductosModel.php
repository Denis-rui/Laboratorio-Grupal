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

    public function buscarProductos(string $q, int $categoriaId, array $filtros = [])
    {
        $sql = "SELECT p.id, p.nombre, c.nombre AS categoria,
                    p.precio, p.stock, p.estado
                FROM productos p
                INNER JOIN categorias c ON p.categoria_id = c.id
                WHERE p.estado = 1";

        $params = [];

        if ($q !== '') {
            $sql .= " AND p.nombre LIKE :q";
            $params[':q'] = '%' . $q . '%';
        }

        if ($categoriaId > 0) {
            $sql .= " AND p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $categoriaId;
        }

        if (!empty($filtros)) {
            $count = count($filtros);
            $filtroParams = [];
            foreach (array_values($filtros) as $i => $valor) {
                $key = ':filtro_' . $i;
                $filtroParams[] = $key;
                $params[$key] = $valor;
            }
            $placeholders = implode(',', $filtroParams);
            $sql .= " AND p.id IN (
                        SELECT vp.producto_id
                        FROM valores_productos vp
                        WHERE vp.valor IN ($placeholders)
                        GROUP BY vp.producto_id
                        HAVING COUNT(DISTINCT vp.valor) = $count
                    )";
        }

        $sql .= " ORDER BY p.nombre ASC";

        $stmt = $this->conectar()->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
