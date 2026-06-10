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

final class AuthenticationMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return Response::redirect($this->loginUrl());
        }

        // Seguridad: validar fingerprint (huella digital).
        $fingerprint = md5(
            (string) $request->getAttribute('user_agent', '') .
            (string) $request->getAttribute('remote_addr', '')
        );

        if (!isset($_SESSION['fingerprint']) || $_SESSION['fingerprint'] !== $fingerprint) {
            $_SESSION = [];
            session_destroy();
            return Response::redirect($this->loginUrl() . '?error=session_invalid');
        }

        $request = $request->withAttribute(
            'auth_user_id',
            (int) $_SESSION['user_id']
        );

        return $handler->handle($request);
    }

    private function loginUrl(): string
    {
        return defined('BASE_URL') ? BASE_URL . 'Login' : '/login';
    }
}
