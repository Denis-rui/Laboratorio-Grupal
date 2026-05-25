<?php

class VentasController extends Controller
{
    private function clientesModel()
    {
        require_once "Models/ClientesModel.php";
        return new ClientesModel();
    }

    private function productosModel()
    {
        require_once "Models/ProductosModel.php";
        return new ProductosModel();
    }

    private function esAdminGlobal()
    {
        $rolNombre = strtolower(trim($_SESSION['rol_nombre'] ?? ''));
        $rolId = (int) ($_SESSION['rol_id'] ?? 0);
        return $rolId === 1 || strpos($rolNombre, 'admin') !== false;
    }

    public function __construct()
    {
        parent::__construct();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_data'])) {
            header("Location: " . BASE_URL . "Login");
            exit();
        }
        $url = $_GET['url'] ?? '';
        $arrUrl = explode('/', $url);
        $metodo = $arrUrl[1] ?? 'index';
        $permisoRequerido = '';
        switch ($metodo) {
            case 'crear':
            case 'guardar':
                $permisoRequerido = 'ventas.crear';
                break;
            case 'editar':
            case 'actualizar':
                $permisoRequerido = 'ventas.editar';
                break;
            case 'eliminar':
                $permisoRequerido = 'ventas.eliminar';
                break;
            case 'listar':
            case 'index':
                $permisoRequerido = 'ventas.listar';
                break;
            case 'ver':
                $permisoRequerido = 'ventas.ver';
                break;
        }
        if ($permisoRequerido !== '' && !$this->esAdminGlobal()) {
            if (!isset($_SESSION['permisos']) || !in_array($permisoRequerido, $_SESSION['permisos'])) {
                $this->accesoDenegado("No tienes permiso para " . $permisoRequerido . ".");
            }
        }
    }
    public function index()
    {
        $this->listar();
    }

    public function listar()
    {
        $data = $this->model->obtenerVentasConRelaciones();
        $this->views->render($this, "listado", $data);
    }

    public function crear()
    {
        $clientesModel = $this->clientesModel();
        $productosModel = $this->productosModel();
        $data = [
            "clientes" => $clientesModel->obtenerClientesActivos(),
            "productos" => $productosModel->obtenerProductosActivos(),
        ];
        $this->views->render($this, "crear", $data);
    }

    public function guardar()
    {
        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        $productoId = (int) ($_POST['producto_id'] ?? 0);
        $cantidad = (int) ($_POST['cantidad'] ?? 0);

        if ($clienteId <= 0 || $productoId <= 0 || $cantidad <= 0) {
            echo "Cliente, producto y cantidad son obligatorios";
            return;
        }

        $producto = $this->productosModel()->obtenerProductoActivoPorId($productoId);
        if (!$producto) {
            echo "El producto seleccionado no existe";
            return;
        }

        $precioUnitario = (float) $producto['precio'];
        $total = $precioUnitario * $cantidad;

        // Temporal: en la implementacion de login/RBAC se debe usar el usuario en sesion.
        $dataVenta = [
            "usuario_id" => 1,
            "cliente_id" => $clienteId,
            "total" => $total,
        ];

        $dataDetalle = [
            "producto_id" => $productoId,
            "cantidad" => $cantidad,
            "precio_unitario" => $precioUnitario,
        ];

        if ($this->model->registrarVentaConDetalle($dataVenta, $dataDetalle)) {
            header("Location: " . BASE_URL . "ventas/listar");
        } else {
            echo "Error al guardar la venta";
        }
    }
}
