<?php

// Obtener el nombre del archivo actual
spl_autoload_register(function ($className) {
    $file = "Libraries/Core/{$className}.php"; // Construir la ruta del archivo
    if (file_exists($file)) { // Verificar si el archivo existe
        require_once $file; // Incluir el archivo
    }
});
