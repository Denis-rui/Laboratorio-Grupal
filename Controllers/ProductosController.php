<?php

class ProductosController extends Controller
{

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
                $permisoRequerido = 'productos.crear';
                break;
            case 'editar':
            case 'actualizar':
                $permisoRequerido = 'productos.editar';
                break;
            case 'eliminar':
                $permisoRequerido = 'productos.eliminar';
                break;
            case 'listar':
            case 'index':
                $permisoRequerido = 'productos.listar';
                break;
            case 'ver':
                $permisoRequerido = 'productos.ver';
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
        $data = $this->model->obtenerProductosConCategoria();
        $this->views->render($this, "listado", $data);
    }

    public function ver($id)
    {
        $data = $this->model->obtenerProductoConCategoriaPorId((int) $id);
        if (!$data) {
            echo "Producto no encontrado";
            return;
        }
        $this->views->render($this, "ver", $data);
    }

    public function crear()
    {
        require_once "Models/CategoriasModel.php";
        $categoriasModel = new CategoriasModel();
        $data = [
            "categorias" => $categoriasModel->obtenerCategorias(),
        ];
        $this->views->render($this, "crear", $data);
    }

    public function guardar()
    {
        $categoriaId = (int) ($_POST['categoria_id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $precio = trim($_POST['precio'] ?? '');
        $stock = (int) ($_POST['stock'] ?? -1);

        if ($categoriaId <= 0 || $nombre === '' || $precio === '' || $stock < 0) {
            echo "Categoria, nombre, precio y stock son obligatorios";
            return;
        }

        if (!is_numeric($precio) || (float) $precio < 0) {
            echo "El precio debe ser un numero valido";
            return;
        }

        $data = [
            "categoria_id" => $categoriaId,
            "nombre" => $nombre,
            "precio" => (float) $precio,
            "stock" => $stock,
            "estado" => 1,
        ];

        if ($this->model->create($data)) {
            header("Location: " . BASE_URL . "productos/listar");
        } else {
            echo "Error al guardar el producto";
        }
    }

    public function editar($id)
    {
        $producto = $this->model->find((int) $id);
        if (!$producto) {
            echo "Producto no encontrado";
            return;
        }

        require_once "Models/CategoriasModel.php";
        $categoriasModel = new CategoriasModel();
        $data = [
            "producto" => $producto,
            "categorias" => $categoriasModel->obtenerCategorias(),
        ];
        $this->views->render($this, "editar", $data);
    }

    public function actualizar()
    {
        $id = (int) ($_POST['id'] ?? 0);
        $categoriaId = (int) ($_POST['categoria_id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $precio = trim($_POST['precio'] ?? '');
        $stock = (int) ($_POST['stock'] ?? -1);

        if ($id <= 0 || $categoriaId <= 0 || $nombre === '' || $precio === '' || $stock < 0) {
            echo "ID, categoria, nombre, precio y stock son obligatorios";
            return;
        }

        if (!is_numeric($precio) || (float) $precio < 0) {
            echo "El precio debe ser un numero valido";
            return;
        }

        $data = [
            "categoria_id" => $categoriaId,
            "nombre" => $nombre,
            "precio" => (float) $precio,
            "stock" => $stock,
        ];

        if ($this->model->update($id, $data)) {
            header("Location: " . BASE_URL . "productos/listar");
        } else {
            echo "Error al actualizar el producto";
        }
    }

    public function eliminar($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            echo "ID de producto invalido";
            return;
        }

        if ($this->model->update($id, ["estado" => 0])) {
            header("Location: " . BASE_URL . "productos/listar");
        } else {
            echo "No se pudo eliminar el producto";
        }
    }
}
