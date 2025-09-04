<?php

namespace App\models;

require_once __DIR__ . '/../config/Database.php';


use App\config\Database;
use PDO;

class Users
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function save(array $data): bool
    {
        $sql = "
            INSERT INTO User (first_name, last_name, email, password, rol, state) 
            VALUES (:first_name, :last_name, :email, :password, :rol, 1)
        ";

        $stmt = $this->db->prepare($sql);
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name'  => $data['last_name'],
            ':email'      => $data['email'],
            ':password'   => $data['password'],
            ':rol'        => $data['rol'] ?? 'client'
        ]);
    }

    public function getAll(): array
    {
        $sql = "SELECT idUsers, first_name, last_name, email, rol, state 
                FROM User WHERE state = 1";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM User WHERE email = :email LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function login(string $email, string $password): array
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return ["success" => true, "message" => "Login exitoso", "user" => $user];
        }

        return ["success" => false, "message" => "Credenciales inválidas"];
    }

    public function delete(int $idUsers): bool
    {
        $sql = "UPDATE User SET state = 0 WHERE idUsers = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $idUsers]);
    }
}
