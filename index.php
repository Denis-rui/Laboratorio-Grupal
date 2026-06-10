<?php

declare(strict_types=1);

use App\Http\Middleware\AuthenticationMiddleware;
use App\Http\Middleware\AuthorizationRBACMiddleware;
use App\Repositories\PermissionRepository;
use Core\Http\ControllerRequestHandler;
use Core\Http\MiddlewareDispatcher;
use Core\Http\NativeSession;
use Core\Http\ServerRequest;

require_once 'Config/Config.php';
require_once 'Libraries/Core/Autoload.php';
require_once 'Libraries/Core/Http/Interfaces.php';
require_once 'Libraries/Core/Http/ServerRequest.php';
require_once 'Libraries/Core/Http/Response.php';
require_once 'Libraries/Core/Http/ControllerRequestHandler.php';
require_once 'Libraries/Core/Http/MiddlewareDispatcher.php';
require_once 'Libraries/Core/Http/SessionInterface.php';
require_once 'Libraries/Core/Http/NativeSession.php';
require_once 'App/Http/Middleware/AuthenticationMiddleware.php';
require_once 'App/Http/Middleware/AuthorizationRBACMiddleware.php';
require_once 'App/Repositories/PermissionRepository.php';

header("Content-Security-Policy: default-src 'self'; script-src 'self'");
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$url = trim((string) ($_GET['url'] ?? 'Login'), '/');
$arrUrl = $url === '' ? ['Login'] : explode('/', $url);
$controlador = ucfirst(strtolower($arrUrl[0] ?? 'Login'));
$metodo = $arrUrl[1] ?? 'index';
$parametros = implode(',', array_slice($arrUrl, 2));

$rutasPublicas = [
    'login/index',
    'login/validar',
    'login/logout',
    'error/index',
    'error/accessdenied',
];

$rutaActual = strtolower($controlador . '/' . $metodo);
$esRutaPublica = in_array($rutaActual, $rutasPublicas, true);

$mapaPermisos = [
    'productos/index' => 'productos.listar',
    'productos/listar' => 'productos.listar',
    'productos/ver' => 'productos.ver',
    'productos/crear' => 'productos.crear',
    'productos/guardar' => 'productos.crear',
    'productos/editar' => 'productos.editar',
    'productos/actualizar' => 'productos.editar',
    'productos/eliminar' => 'productos.eliminar',
    'categorias/index' => 'categorias.listar',
    'categorias/listar' => 'categorias.listar',
    'categorias/ver' => 'categorias.ver',
    'categorias/crear' => 'categorias.crear',
    'categorias/guardar' => 'categorias.crear',
    'categorias/editar' => 'categorias.editar',
    'categorias/actualizar' => 'categorias.editar',
    'categorias/eliminar' => 'categorias.eliminar',
    'clientes/index' => 'clientes.listar',
    'clientes/listar' => 'clientes.listar',
    'clientes/ver' => 'clientes.ver',
    'clientes/crear' => 'clientes.crear',
    'clientes/guardar' => 'clientes.crear',
    'clientes/editar' => 'clientes.editar',
    'clientes/actualizar' => 'clientes.editar',
    'clientes/eliminar' => 'clientes.eliminar',
    'ventas/index' => 'ventas.listar',
    'ventas/listar' => 'ventas.listar',
    'ventas/ver' => 'ventas.ver',
    'ventas/crear' => 'ventas.crear',
    'ventas/guardar' => 'ventas.crear',
    'ventas/editar' => 'ventas.editar',
    'ventas/actualizar' => 'ventas.editar',
    'ventas/eliminar' => 'ventas.eliminar',
    'usuarios/index' => 'usuarios.listar',
    'usuarios/listar' => 'usuarios.listar',
    'usuarios/ver' => 'usuarios.ver',
    'usuarios/crear' => 'usuarios.crear',
    'usuarios/guardar' => 'usuarios.crear',
];

$handler = new ControllerRequestHandler($controlador, $metodo, $parametros);
$dispatcher = new MiddlewareDispatcher($handler);
$session = new NativeSession();

if (!$esRutaPublica) {
    $dispatcher->add(new AuthenticationMiddleware($session));

    $permisoRequerido = $mapaPermisos[$rutaActual] ?? null;
    if ($permisoRequerido !== null) {
        $dispatcher->add(
            new AuthorizationRBACMiddleware(
                $permisoRequerido,
                new PermissionRepository()
            )
        );
    }
}

$request = ServerRequest::fromGlobals()
    ->withAttribute('route', $rutaActual)
    ->withAttribute('controller', $controlador)
    ->withAttribute('method', $metodo);

$response = $dispatcher->handle($request);

http_response_code($response->getStatusCode());

foreach ($response->getHeaders() as $name => $value) {
    header($name . ': ' . $value);
}

echo $response->getBody();
