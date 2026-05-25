<?php

class ProductosController extends Controller
{
    public function index()
    {
        $this->listar();
    }

    public function listar()
    {
        $data = $this->model->listarConCategoria();
        $this->views->render($this, "listado", $data);
    }

    public function ver($id)
    {
        $data = $this->model->verConCategoria((int) $id);
        if (!$data) {
            echo "Producto no encontrado";
            return;
        }
        $this->views->render($this, "ver", $data);
    }

    public function crear()
    {
        $data = [
            "categorias" => $this->model->obtenerCategorias(),
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

        $data = [
            "producto" => $producto,
            "categorias" => $this->model->obtenerCategorias(),
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
