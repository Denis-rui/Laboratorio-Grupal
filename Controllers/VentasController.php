<?php

class VentasController extends Controller
{
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
                $permisoRequerido = 'crear';
                break;
            case 'editar':
            case 'actualizar':
                $permisoRequerido = 'editar';
                break;
            case 'eliminar':
                $permisoRequerido = 'eliminar';
                break;
            case 'listar':
            case 'index':
                $permisoRequerido = 'listar';
                break;
            case 'ver':
                $permisoRequerido = 'ver_uno';
                break;
        }
        if ($permisoRequerido !== '') {
            if (!isset($_SESSION['permisos']) || !in_array($permisoRequerido, $_SESSION['permisos'])) {
                die("Acceso denegado: No tienes permiso para " . $permisoRequerido . ".");
            }
        }
    }
    public function index()
    {
        $this->listar();
    }

    public function listar()
    {
        $data = $this->model->listarConRelaciones();
        $this->views->render($this, "listado", $data);
    }

    public function crear()
    {
        $data = [
            "clientes" => $this->model->obtenerClientes(),
            "productos" => $this->model->obtenerProductos(),
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

        $producto = $this->model->obtenerProductoPorId($productoId);
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
