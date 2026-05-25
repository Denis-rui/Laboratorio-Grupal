<?php

class UsuariosController extends Controller
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
    public function ver($id)
    {
        $data = $this->model->where(["id" => $id, "estado" => 1])->first();
        $this->views->render($this, "crear", $data);
    }
    public function crear()
    {
        $this->views->render($this, "crear");
    }
    public function guardar()
    {
        $data = [
            "nombre" => $_POST['nombre'],
            "correo" => $_POST['correo'],
            "clave" => password_hash($_POST['clave'], PASSWORD_DEFAULT),
            "estado" => 1,
        ];
        if ($this->model->create($data)) {
            header("Location: " . BASE_URL . "usuarios/listar");
        } else {
            echo "Error al guardar el usuario";
        }
    }
}
