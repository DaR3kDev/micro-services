<?php

namespace App\users\services;

use App\users\models\Users;
use App\utils\ArrayHelper;

class UsersService
{
    private readonly Users $userModel;

    public function __construct()
    {
        $this->userModel = new Users();
    }

    public function addUser($user): array
    {
        $userArray = ArrayHelper::toArray($user->user ?? $user);
        return $this->userModel->save($userArray);
    }

    public function updateUser(int $id, $user): array
    {
        $userArray = ArrayHelper::toArray($user->user ?? $user);
        return $this->userModel->update($id, $userArray);
    }

    public function listUsers(): array
    {
        return $this->userModel->getAll();
    }

    public function findUserByEmail(string $email): ?array
    {
        return $this->userModel->findByEmail($email);
    }

    public function loginUser($data): array
    {
        $dataArray = ArrayHelper::toArray($data);
        return $this->userModel->login($dataArray['email'], $dataArray['password']);
    }

    public function deactivateUser(int $id): bool
    {
        return $this->userModel->deactivateUser($id);
    }

    public function hardDeleteUser(int $id): bool
    {
        return $this->userModel->hardDelete($id);
    }
}
