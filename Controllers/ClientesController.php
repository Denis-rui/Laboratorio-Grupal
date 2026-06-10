<?php
class ClientesController extends Controller
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
        $data = $this->model->where(["estado" => 1])->get();
        $this->model->resetQuery();
        $this->views->render($this, "listado", $data);
    }

    public function crear()
    {
        $this->views->render($this, "crear");
    }

    public function guardar()
    {
        $data = [
            "nombre"   => $_POST['nombre'],
            "dni"      => $_POST['dni'],
            "telefono" => $_POST['telefono'],
            "estado"   => 1
        ];
        if ($this->model->create($data)) {
            header("Location: " . BASE_URL . "Clientes/listar");
        } else {
            echo "Error al guardar";
        }
        exit();
    }

    public function editar($id)
    {
        $data = $this->model->where(["id" => $id])->first();
        $this->model->resetQuery();
        $this->views->render($this, "editar", $data);
    }

    public function actualizar($id)
    {
        $data = [
            "nombre"   => $_POST['nombre'],
            "dni"      => $_POST['dni'],
            "telefono" => $_POST['telefono']
        ];
        if ($this->model->update($id, $data)) {
            header("Location: " . BASE_URL . "Clientes/listar");
        } else {
            echo "Error al actualizar";
        }
        exit();
    }

    public function eliminar($id)
    {
        $this->model->delete($id);
        header("Location: " . BASE_URL . "Clientes/listar");
        exit();
    }
}
