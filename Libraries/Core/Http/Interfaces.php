<?php

namespace Core\Http;

interface ResponseInterface
{
}

interface ServerRequestInterface
{
    public function getMethod(): string;

    public function getAttribute(string $name, mixed $default = null): mixed;

    public function withAttribute(string $name, mixed $value): self;
}

interface RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface;
}

interface MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface;
}
