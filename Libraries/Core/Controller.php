<?php
// Sirver para cargar vistas y el modelo`

class Controller
{
    protected $model, $views;
    function __construct()
    {
        $this->views = new Views();
        $this->loadModel();
    }

    protected function accesoDenegado($mensaje = "Acceso denegado")
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['access_denied_message'] = $mensaje;
        header("Location: " . BASE_URL . "Error/accessDenied");
        exit();
    }

    public function loadModel()
    {
        $controllerName = get_class($this);
        $modelName = str_replace("Controller", "Model", $controllerName);

        if ($controllerName === "UsersController") {
            $modelName = "UsuariosModel";
        }

        $ModelPath = "Models/{$modelName}.php";
        if (file_exists($ModelPath)) {
            require_once $ModelPath;
            $this->model = new $modelName();
        }
    }
}
