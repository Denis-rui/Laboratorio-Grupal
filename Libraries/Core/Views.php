<?php

class Views
{
    public function render($controller, $view, $data = [])
    {
        $controller = str_replace("Controller", "", get_class($controller)); // Obtiene el nombre de la clase del controlador, por ejemplo: HomeController
        $viewPath = "Views/{$controller}/{$view}.php"; // Construye la ruta de la vista, por ejemplo: Views/HomeController/index.php
        if (file_exists($viewPath)) { // Verifica si la vista existe
            $contentView =  $viewPath; // Si existe, la incluye

            require_once "Views/Layouts/main.php"; // Luego incluye el layout principal, que es el que se encarga de mostrar la vista dentro de su estructura
        }
    }
}
