<?php

declare(strict_types=1);

namespace App\Repositories;

final class PermissionRepository
{
    public function checkUserPermission(int $userId, string $permission): bool
    {
        require_once 'Models/LoginModel.php';

        $model = new \LoginModel();
        return $model->checkUserPermission($userId, $permission);
    }
}
