<?php

namespace App\Http\Middleware;

require_once __DIR__ . '/../../../Libraries/Core/Http/Interfaces.php';

use Core\Http\MiddlewareInterface;
use Core\Http\RequestHandlerInterface;
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
            header('Location: ' . $this->loginUrl());
            exit;
        }

        // Seguridad: validar fingerprint (huella digital).
        $fingerprint = md5(
            ($_SERVER['HTTP_USER_AGENT'] ?? '') .
            ($_SERVER['REMOTE_ADDR'] ?? '')
        );

        if (!isset($_SESSION['fingerprint']) || $_SESSION['fingerprint'] !== $fingerprint) {
            session_destroy();
            header('Location: ' . $this->loginUrl() . '?error=session_invalid');
            exit;
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
