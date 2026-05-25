<?php

class LoginController extends Controller
{
    private function obtenerRutaPostLogin()
    {
        $rolNombre = strtolower(trim($_SESSION['rol_nombre'] ?? ''));
        $rolId = (int) ($_SESSION['rol_id'] ?? 0);
        if ($rolId === 1 || strpos($rolNombre, 'admin') !== false) {
            return 'Productos/listar';
        }

        $permisos = $_SESSION['permisos'] ?? [];
        $rutas = [
            'ventas.listar' => 'Ventas/listar',
            'clientes.listar' => 'Clientes/listar',
            'productos.listar' => 'Productos/listar',
            'categorias.listar' => 'Categorias/listar',
        ];

        foreach ($rutas as $permiso => $ruta) {
            if (in_array($permiso, $permisos, true)) {
                return $ruta;
            }
        }

        return 'Error/accessDenied';
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_data'])) {
            header("Location: " . BASE_URL . $this->obtenerRutaPostLogin());
            exit();
        }

        $this->views->render($this, "index", [], false);
    }

    public function validar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "Login");
            exit();
        }

        $correo = trim($_POST['correo'] ?? '');
        $clave = $_POST['clave'] ?? '';

        if ($correo === '' || $clave === '') {
            header("Location: " . BASE_URL . "Login?error=1");
            exit();
        }

        $user = $this->model->validarCredenciales($correo, $clave);

        if ($user) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_data'] = $user;

            $permisos = $this->model->obtenerPermisosDelUsuario($user['id']);
            $_SESSION['permisos'] = $permisos;

            $rol = $this->model->obtenerRolDelUsuario($user['id']);
            $_SESSION['rol_id'] = $rol['id'] ?? null;
            $_SESSION['rol_nombre'] = $rol['nombre'] ?? null;

            header("Location: " . BASE_URL . $this->obtenerRutaPostLogin());
            exit();
        }

        header("Location: " . BASE_URL . "Login?error=1");
        exit();
    }
    
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: " . BASE_URL . "Login");
        exit();
    }
}
