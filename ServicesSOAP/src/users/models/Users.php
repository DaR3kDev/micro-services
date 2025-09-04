<?php

namespace App\users\models;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../validators/UserValidator.php';

use PDO;
use App\config\Database;
use App\users\validators\UserValidator;

class Users
{
    private readonly PDO $db;
    private readonly UserValidator $validator;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->validator = new UserValidator($this);
    }

    public function save(array $data): array
    {
        $errors = $this->validator->validate($data);
        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("
            INSERT INTO User (first_name, last_name, email, password, rol, state)
            VALUES (:first_name, :last_name, :email, :password, :rol, 1)
        ");

        $success = $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name'  => $data['last_name'],
            ':email'      => $data['email'],
            ':password'   => $data['password'],
            ':rol'        => $data['rol'] ?? 'client'
        ]);

        return $success
            ? ['success' => true, 'message' => 'Usuario registrado exitosamente.']
            : ['success' => false, 'errors' => ['Error al registrar el usuario.']];
    }

    public function getAll(): array
    {
        return $this->db
            ->query("SELECT idUsers, first_name, last_name, email, rol, state 
                     FROM User WHERE state = 1")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM User WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function login(string $email, string $password): array
    {
        $user = $this->findByEmail($email);
        return $user && password_verify($password, $user['password'])
            ? ['success' => true, 'message' => 'Login exitoso', 'user' => $user]
            : ['success' => false, 'message' => 'Credenciales inválidas'];
    }


    public function update(int $idUsers, array $data): array
    {
        // Validar datos
        $errors = $this->validator->validate($data, $idUsers);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $fields = [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'rol'        => $data['rol'] ?? 'client'
        ];

        // Actualizar password si viene
        if (!empty($data['password'])) {
            $fields['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $setParts = [];
        foreach ($fields as $key => $value) {
            $setParts[] = "$key = :$key";
        }
        $setString = implode(', ', $setParts);

        $stmt = $this->db->prepare("UPDATE User SET $setString WHERE idUsers = :id");
        $fields['id'] = $idUsers;

        $success = $stmt->execute($fields);

        return $success
            ? ['success' => true, 'message' => 'Usuario actualizado correctamente.']
            : ['success' => false, 'errors' => ['Error al actualizar el usuario.']];
    }

    public function deactivateUser(int $idUsers): bool
    {
        $stmt = $this->db->prepare("UPDATE User SET state = 0 WHERE idUsers = :id");
        return $stmt->execute([':id' => $idUsers]);
    }

    public function hardDelete(int $idUsers): bool
    {
        $stmt = $this->db->prepare("DELETE FROM User WHERE idUsers = :id");
        return $stmt->execute([':id' => $idUsers]);
    }
}
