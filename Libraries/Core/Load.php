<?php
$controllerFile = "Controllers/{$controlador}Controller.php";
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = $controlador . "Controller";
    $controllerObject = new $controllerClass();
    if (method_exists($controllerObject, $metodo)) {
        $controllerObject->$metodo($parametros);
    } else {
        require_once "Controllers/ErrorController.php";
    }
} else {
    require_once "Controllers/ErrorController.php";
}
