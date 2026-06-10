<?php

require_once "Libraries/Core/Http/NativeSession.php";

use Core\Http\NativeSession;

class LoginController extends Controller
{
    private NativeSession $session;

    private function obtenerRutaPostLogin()
    {
        $rolNombre = strtolower(trim((string) $this->session->get('rol_nombre', '')));
        $rolId = (int) $this->session->get('rol_id', 0);
        if ($rolId === 1 || strpos($rolNombre, 'admin') !== false) {
            return 'Productos/listar';
        }

        $permisos = $this->session->get('permisos', []);
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
        $this->session = new NativeSession();
    }

    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($this->session->has('user_data')) {
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

            $this->session->regenerate();

            $this->session->set('user_data', $user);
            $this->session->set('user_id', (int) $user['id']);
            $this->session->set('fingerprint', md5(
                ($_SERVER['HTTP_USER_AGENT'] ?? '') .
                ($_SERVER['REMOTE_ADDR'] ?? '')
            ));

            $permisos = $this->model->obtenerPermisosDelUsuario($user['id']);
            $this->session->set('permisos', $permisos);

            $rol = $this->model->obtenerRolDelUsuario($user['id']);
            $this->session->set('rol_id', $rol['id'] ?? null);
            $this->session->set('rol_nombre', $rol['nombre'] ?? null);

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
        $this->session->destroy();
        header("Location: " . BASE_URL . "Login");
        exit();
    }
}
