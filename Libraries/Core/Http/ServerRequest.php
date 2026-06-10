<?php

declare(strict_types=1);

namespace Core\Http;

require_once __DIR__ . '/Interfaces.php';

final class ServerRequest implements ServerRequestInterface
{
    private string $method;
    private array $attributes;

    public function __construct(string $method, array $attributes = [])
    {
        $this->method = strtoupper($method);
        $this->attributes = $attributes;
    }

    public static function fromGlobals(): self
    {
        return new self(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            [
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? '',
            ]
        );
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute(string $name, mixed $value): self
    {
        $request = clone $this;
        $request->attributes[$name] = $value;
        return $request;
    }
}
