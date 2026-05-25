<?php

class UsersController extends Controller
{
    public function index()
    {
        $this->listar();
    }

    public function listar()
    {
        $data = $this->model->where(["estado" => 1])->get();
        // print_r("<pre>");
        // var_dump($data);
        // print_r("</pre>");
        // echo json_encode($data);
        $this->views->render($this, "listado", $data);
    }
    public function ver($id)
    {
        $data = $this->model->where(["id" => $id, "estado" => 1])->first();
        print_r("<pre>");
        var_dump($data);
        print_r("</pre>");
        echo json_encode($data);
    }
    public function crear()
    {
        $this->views->render($this, "crear");
    }
    public function guardar()
    {
        $data = [
            "correo" => $_POST['correo'],
            "clave" => password_hash($_POST['clave'], PASSWORD_DEFAULT),
        ];
        if ($this->model->create($data)) {
            header("Location: " . BASE_URL . "users/listar");
        } else {
            echo "Error al guardar el usuario";
        }
    }
}
