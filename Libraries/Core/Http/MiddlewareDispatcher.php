<?php

declare(strict_types=1);

namespace Core\Http;

require_once __DIR__ . '/Interfaces.php';

final class MiddlewareDispatcher implements RequestHandlerInterface
{
    private array $middlewares = [];
    private RequestHandlerInterface $fallbackHandler;

    public function __construct(RequestHandlerInterface $fallbackHandler)
    {
        $this->fallbackHandler = $fallbackHandler;
    }

    public function add(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            function (callable $nextStack, MiddlewareInterface $middleware): callable {
                return function (ServerRequestInterface $req) use ($nextStack, $middleware): ResponseInterface {
                    $wrapper = new class($nextStack) implements RequestHandlerInterface {
                        private $next;

                        public function __construct(callable $next)
                        {
                            $this->next = $next;
                        }

                        public function handle(ServerRequestInterface $request): ResponseInterface
                        {
                            return ($this->next)($request);
                        }
                    };

                    return $middleware->process($req, $wrapper);
                };
            },
            fn(ServerRequestInterface $req): ResponseInterface => $this->fallbackHandler->handle($req)
        );

        return $pipeline($request);
    }
}
