<?php

class CategoriasController extends Controller
{
    public function index()
    {
        $this->listar();
    }

    public function listar()
    {
        $data = $this->model->orderBy("id", "ASC")->get();
        $this->views->render($this, "listado", $data);
    }

    public function ver($id)
    {
        $data = $this->model->find((int)$id);
        if (!$data) {
            echo "Categoria no encontrada";
            return;
        }
        $this->views->render($this, "ver", $data);
    }

    public function crear()
    {
        $this->views->render($this, "crear");
    }

    public function guardar()
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($nombre === '') {
            echo "El nombre de la categoria es obligatorio";
            return;
        }

        $data = [
            "nombre" => $nombre,
            "descripcion" => $descripcion !== '' ? $descripcion : null,
        ];

        if ($this->model->create($data)) {
            header("Location: " . BASE_URL . "categorias/listar");
        } else {
            echo "Error al guardar la categoria";
        }
    }

    public function editar($id)
    {
        $data = $this->model->find((int)$id);
        if (!$data) {
            echo "Categoria no encontrada";
            return;
        }
        $this->views->render($this, "editar", $data);
    }

    public function actualizar()
    {
        $id = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($id <= 0 || $nombre === '') {
            echo "ID y nombre de categoria son obligatorios";
            return;
        }

        $data = [
            "nombre" => $nombre,
            "descripcion" => $descripcion !== '' ? $descripcion : null,
        ];

        if ($this->model->update($id, $data)) {
            header("Location: " . BASE_URL . "categorias/listar");
        } else {
            echo "Error al actualizar la categoria";
        }
    }

    public function eliminar($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            echo "ID de categoria invalido";
            return;
        }

        try {
            if ($this->model->delete($id)) {
                header("Location: " . BASE_URL . "categorias/listar");
            } else {
                echo "No se pudo eliminar la categoria";
            }
        } catch (Throwable $e) {
            echo "No se puede eliminar la categoria porque tiene productos asociados";
        }
    }
}
