<?php

declare(strict_types=1);

namespace Core\Http;

require_once __DIR__ . '/Interfaces.php';
require_once __DIR__ . '/Response.php';

final class ControllerRequestHandler implements RequestHandlerInterface
{
    private string $controller;
    private string $method;
    private string $parameters;

    public function __construct(
        string $controller,
        string $method,
        string $parameters = ''
    ) {
        $this->controller = $controller;
        $this->method = $method;
        $this->parameters = $parameters;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $controllerFile = 'Controllers/' . $this->controller . 'Controller.php';
        $controllerClass = $this->controller . 'Controller';

        if (!file_exists($controllerFile)) {
            return new Response('Error 404: Controlador no encontrado', 404);
        }

        require_once $controllerFile;
        $controllerObject = new $controllerClass();

        if (!method_exists($controllerObject, $this->method)) {
            return new Response('Error 404: Metodo no encontrado', 404);
        }

        ob_start();
        $controllerObject->{$this->method}($this->parameters);
        $body = (string) ob_get_clean();

        $statusCode = http_response_code();
        if (!is_int($statusCode) || $statusCode < 100) {
            $statusCode = 200;
        }

        return new Response($body, $statusCode);
    }
}
