<?php
class CategoriasController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_data'])) {
            header("Location: " . BASE_URL . "Login");
            exit();
        }

        $url = $_GET['url'] ?? '';
        $arrUrl = explode('/', $url);
        $metodo = $arrUrl[1] ?? 'index';

        $permisoRequerido = '';
        switch ($metodo) {
            case 'crear': case 'guardar': $permisoRequerido = 'crear'; break;
            case 'editar': case 'actualizar': $permisoRequerido = 'editar'; break;
            case 'eliminar': $permisoRequerido = 'eliminar'; break;
            case 'listar': case 'index': $permisoRequerido = 'listar'; break;
            case 'ver': $permisoRequerido = 'ver_uno'; break;
        }

        if ($permisoRequerido !== '') {
            if (!isset($_SESSION['permisos']) || !in_array($permisoRequerido, $_SESSION['permisos'])) {
                die("Acceso denegado: No tienes permiso para " . $permisoRequerido . ".");
            }
        }
    }

    public function index() {
        echo "Listado de categorías (simulado)";
    }
}
