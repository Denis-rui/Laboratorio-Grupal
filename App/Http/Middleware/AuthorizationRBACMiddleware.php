<?php

declare(strict_types=1);

namespace App\Http\Middleware;

require_once __DIR__ . '/../../../Libraries/Core/Http/Interfaces.php';
require_once __DIR__ . '/../../../Libraries/Core/Http/Response.php';

use Core\Http\MiddlewareInterface;
use Core\Http\RequestHandlerInterface;
use Core\Http\Response;
use Core\Http\ResponseInterface;
use Core\Http\ServerRequestInterface;

final class AuthorizationRBACMiddleware implements MiddlewareInterface
{
    private string $permisoRequerido;
    private object $repositorio;

    public function __construct(string $permiso, object $repo)
    {
        $this->permisoRequerido = $permiso;
        $this->repositorio = $repo;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $userId = (int) $request->getAttribute('auth_user_id');
        $tieneAcceso = $this->repositorio->checkUserPermission(
            $userId,
            $this->permisoRequerido
        );

        if (!$tieneAcceso) {
            return new Response(
                'Error 403: No tienes permiso para: ' . $this->permisoRequerido,
                403,
                ['Content-Type' => 'text/plain; charset=UTF-8']
            );
        }

        return $handler->handle($request);
    }
}
