<?php

class ProductosController extends Controller
{
    public function __construct()
    {
        parent::__construct();
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


    public function apiGetFiltros()
    {
        header('Content-Type: application/json; charset=utf-8');

        $categoriaId = (int) ($_GET['categoria_id'] ?? 0);

        if ($categoriaId <= 0) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Debe proporcionar una categoría válida."
            ]);
            return;
        }

        $filtros = $this->model->obtenerFiltrosPorCategoria($categoriaId);

        $resultado = [];

        foreach ($filtros as $fila) {
            $atributo = $fila['atributo'];
            $valor = $fila['valor'];

            if (!isset($resultado[$atributo])) {
                $resultado[$atributo] = [];
            }

            if (!in_array($valor, $resultado[$atributo])) {
                $resultado[$atributo][] = $valor;
            }
        }

        echo json_encode([
            "success" => true,
            "filtros" => $resultado
        ]);
    }

    public function apiBuscar()
    {
        header('Content-Type: application/json; charset=utf-8');

        $q           = trim($_GET['q'] ?? '');
        $categoriaId = (int) ($_GET['categoria_id'] ?? 0);
        $filtros     = $_GET['filtros'] ?? [];

        if (!is_array($filtros)) {
            $filtros = [];
        }

        $productos = $this->model->buscarProductos($q, $categoriaId, $filtros);

        echo json_encode($productos);
    }
}
