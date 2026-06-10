<?php

declare(strict_types=1);

namespace Core\Http;

require_once __DIR__ . '/SessionInterface.php';

final class NativeSession implements SessionInterface
{
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
