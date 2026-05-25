<?php

class Views
{
    public function render($controller, $view, $data = [], $useLayout = true)
    {
        $controller = str_replace("Controller", "", get_class($controller)); // Obtiene el nombre de la clase del controlador, por ejemplo: HomeController
        if ($controller === "Users") {
            $controller = "Usuarios";
        }
        $viewPath = "Views/{$controller}/{$view}.php"; // Construye la ruta de la vista, por ejemplo: Views/HomeController/index.php
        if (file_exists($viewPath)) { // Verifica si la vista existe
            $contentView =  $viewPath; // Si existe, la incluye

            if ($useLayout) {
                $menusNavbar = [];

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($_SESSION['user_data'])) {
                    $menusModelPath = "Models/MenusModel.php";

                    if (file_exists($menusModelPath)) {
                        require_once $menusModelPath;

                        $menusModel = new MenusModel();
                        $rolId = (int) ($_SESSION['rol_id'] ?? 0);
                        $rolNombre = strtolower(trim($_SESSION['rol_nombre'] ?? ''));
                        $esAdmin = $rolId === 1 || strpos($rolNombre, 'admin') !== false;

                        $menusNavbar = $menusModel->getMenusPorRol($rolId, $esAdmin);
                    }
                }

                require_once "Views/Layouts/main.php"; // Luego incluye el layout principal, que es el que se encarga de mostrar la vista dentro de su estructura
            } else {
                require_once $contentView; // Renderiza solo la vista sin el layout
            }
        }
    }
}
