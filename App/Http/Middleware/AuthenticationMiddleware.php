<?php

declare(strict_types=1);

namespace App\Http\Middleware;

require_once __DIR__ . '/../../../Libraries/Core/Http/Interfaces.php';
require_once __DIR__ . '/../../../Libraries/Core/Http/Response.php';
require_once __DIR__ . '/../../../Libraries/Core/Http/SessionInterface.php';

use Core\Http\MiddlewareInterface;
use Core\Http\RequestHandlerInterface;
use Core\Http\Response;
use Core\Http\ResponseInterface;
use Core\Http\ServerRequestInterface;
use Core\Http\SessionInterface;

final class AuthenticationMiddleware implements MiddlewareInterface
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (!$this->session->has('user_id')) {
            return Response::redirect($this->loginUrl());
        }

        // Seguridad: validar fingerprint (huella digital).
        $fingerprint = md5(
            (string) $request->getAttribute('user_agent', '') .
            (string) $request->getAttribute('remote_addr', '')
        );

        if ($this->session->get('fingerprint') !== $fingerprint) {
            $this->session->destroy();
            return Response::redirect($this->loginUrl() . '?error=session_invalid');
        }

        $request = $request->withAttribute(
            'auth_user_id',
            (int) $this->session->get('user_id')
        );

        return $handler->handle($request);
    }

    private function loginUrl(): string
    {
        return defined('BASE_URL') ? BASE_URL . 'Login' : '/login';
    }
}
