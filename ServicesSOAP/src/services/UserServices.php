<?php

namespace App\services;

require_once __DIR__ . "/../models/Users.php";

use App\models\Users;

class UsersService
{
    private Users $userModel;

    public function __construct()
    {
        $this->userModel = new Users();
    }

    public function addUser($user)
    {
        $userArray = $this->toArray($user->user);
        return $this->userModel->save($userArray);
    }

    public function listUsers()
    {
        return $this->userModel->getAll();
    }

    public function findUserByEmail($email)
    {
        return $this->userModel->findByEmail($email);
    }

    public function loginUser($data)
    {
        $dataArray = $this->toArray($data);
        return $this->userModel->login($dataArray['email'], $dataArray['password']);
    }

    private function toArray($obj): array
    {
        return json_decode(json_encode($obj), true);
    }
}
