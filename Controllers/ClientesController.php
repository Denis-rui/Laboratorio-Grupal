<?php

class ClientesController extends Controller
{
    public function index()
    {
        $this->listar();
    }

    public function listar()
    {
        $data = $this->model->where(["estado" => 1])->get();
        $this->views->render($this, "listado", $data);
    }

    public function crear(){
        $this->views->render($this, "crear");
    }

    public function editar(){

    }

    public function eliminar(){

    }

    public function guardar(){
        $data = [
            "nombre" => $_POST['nombre'],
            "dni" => $_POST['dni'],
            "telefono" => $_POST['telefono']
        ];
        if ($this->model->create($data)) {
            header("Location: " . BASE_URL . "clientes/listar");
        } else {
            echo "Error al guardar el cliente";
        }
    }
}