<?php

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        require_once "Models/UsersModel.php";
        $this->model = new UsersModel();
    }

    public function index()
    {
        $this->views->render($this, "index", [], false);
    }

    public function validar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['correo'];
            $clave = $_POST['clave'];

            $user = $this->model->where(["correo" => $correo, "estado" => 1])->first();

            if ($user && password_verify($clave, $user['clave'])) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_data'] = $user;
                
                $permisos = $this->model->getPermisos($user['id']);
                $_SESSION['permisos'] = $permisos;

                header("Location: " . BASE_URL . "Home");
            } else {
                header("Location: " . BASE_URL . "Login?error=1");
            }
        }
    }
    
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: " . BASE_URL . "Login");
    }
}
