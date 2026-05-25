<?php
session_start();
$url = $_GET['url'] ?? 'Login';


// funcioni para partir una cadena

$arrUrl = explode('/', $url);
$controlador = ucwords($arrUrl[0] ?? 'Login');
$metodo = $arrUrl[1] ?? 'index';
$parametros = "";

if (!empty($arrUrl[2])) {
    if ($arrUrl[2] != "") {
        for ($i = 2; $i < count($arrUrl); $i++) {
            $parametros .= $arrUrl[$i] . ",";
        }
        $parametros = rtrim($parametros, ",");
    }
}
require_once "Libraries/Core/Autoload.php";
require_once "Libraries/Core/Load.php";
