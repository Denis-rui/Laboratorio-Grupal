<?php

$url = $_GET['url'] ?? 'Home';


// funcioni para partir una cadena

$arrUrl = explode('/', $url);
$controlador = ucwords($arrUrl[0] ?? 'Home');
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>