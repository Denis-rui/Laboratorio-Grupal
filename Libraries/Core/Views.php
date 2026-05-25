<?php

class Views
{
    public function render($controller, $view, $data = [], $useLayout = true)
    {
        $controllerBase = basename(str_replace('\\', '/', get_class($controller)));
        $controllerBase = str_replace("Controller", "", $controllerBase);

        if ($controllerBase === "Users") {
            $controllerBase = "Usuarios";
        }

        $viewPath = "Views/{$controllerBase}/{$view}.php";

        if (file_exists($viewPath)) {
            $contentView = $viewPath;

            if ($useLayout) {
                $menusNavbar = [];

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($_SESSION['user_data'])) {
                    require_once "Models/MenusModel.php";
                    $menusModel = new MenusModel();
                    $rolId = (int) ($_SESSION['rol_id'] ?? 0);
                    $rolNombre = strtolower(trim($_SESSION['rol_nombre'] ?? ''));
                    $esAdmin = $rolId === 1 || strpos($rolNombre, 'admin') !== false;

                    $menusNavbar = $menusModel->obtenerMenusPorRol($rolId, $esAdmin);
                }

                require_once "Views/Layouts/main.php";
            } else {
                require_once $contentView;
            }
        }
    }
}
