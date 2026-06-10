<?php

declare(strict_types=1);

namespace Core\Http;

interface SessionInterface
{
    public function has(string $key): bool;

    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value): void;

    public function regenerate(): void;

    public function destroy(): void;
}
