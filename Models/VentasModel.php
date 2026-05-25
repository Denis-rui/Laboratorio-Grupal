<?php

class VentasModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = "ventas";
    }

    public function listarConRelaciones()
    {
        return $this->select("ventas.id, clientes.nombre AS cliente, productos.nombre AS producto, venta_detalle.cantidad, venta_detalle.precio_unitario, ventas.fecha, ventas.total")
            ->join("clientes", "ventas.cliente_id = clientes.id")
            ->join("venta_detalle", "ventas.id = venta_detalle.venta_id")
            ->join("productos", "venta_detalle.producto_id = productos.id")
            ->orderBy("ventas.id", "ASC")
            ->get();
    }

    public function obtenerClientes()
    {
        $sql = "SELECT id, nombre FROM clientes WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductos()
    {
        $sql = "SELECT id, nombre, precio FROM productos WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conectar()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductoPorId($id)
    {
        $sql = "SELECT id, nombre, precio FROM productos WHERE id = :id AND estado = 1";
        $stmt = $this->conectar()->prepare($sql);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarVentaConDetalle($dataVenta, $dataDetalle)
    {
        $db = $this->conectar();
        try {
            $db->beginTransaction();

            $sqlVenta = "INSERT INTO ventas (usuario_id, cliente_id, total) VALUES (:usuario_id, :cliente_id, :total)";
            $stmtVenta = $db->prepare($sqlVenta);
            $stmtVenta->bindValue(':usuario_id', $dataVenta['usuario_id'], PDO::PARAM_INT);
            $stmtVenta->bindValue(':cliente_id', $dataVenta['cliente_id'], PDO::PARAM_INT);
            $stmtVenta->bindValue(':total', $dataVenta['total']);
            $stmtVenta->execute();

            $ventaId = (int)$db->lastInsertId();

            $sqlDetalle = "INSERT INTO venta_detalle (venta_id, producto_id, cantidad, precio_unitario) VALUES (:venta_id, :producto_id, :cantidad, :precio_unitario)";
            $stmtDetalle = $db->prepare($sqlDetalle);
            $stmtDetalle->bindValue(':venta_id', $ventaId, PDO::PARAM_INT);
            $stmtDetalle->bindValue(':producto_id', $dataDetalle['producto_id'], PDO::PARAM_INT);
            $stmtDetalle->bindValue(':cantidad', $dataDetalle['cantidad'], PDO::PARAM_INT);
            $stmtDetalle->bindValue(':precio_unitario', $dataDetalle['precio_unitario']);
            $stmtDetalle->execute();

            $db->commit();
            return true;
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }
}
