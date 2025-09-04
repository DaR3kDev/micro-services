<?php

namespace App\products\models;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../validators/ProductValidator.php';

use PDO;
use App\config\Database;
use App\products\validators\ProductValidator;

class Product
{
    private readonly PDO $db;
    private readonly ProductValidator $validator;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->validator = new ProductValidator($this);
    }

    public function save(array $data): array
    {
        $errors = $this->validator->validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $stmt = $this->db->prepare("
            INSERT INTO Product (name, brand, category_id, price, quantity, description)
            VALUES (:name, :brand, :category_id, :price, :quantity, :description)
        ");

        $success = $stmt->execute([
            ':name'       => $data['name'],
            ':brand'      => $data['brand'],
            ':category_id' => $data['category_id'],
            ':price'      => $data['price'],
            ':quantity'   => $data['quantity'],
            ':description' => $data['description'] ?? null
        ]);

        return $success
            ? ['success' => true, 'message' => 'Producto creado exitosamente.']
            : ['success' => false, 'errors' => ['Error al registrar el producto.']];
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT p.idProduct, p.name, p.brand, c.name AS category, p.price, p.quantity, p.description
            FROM Product p
            INNER JOIN Category c ON p.category_id = c.idCategory
            WHERE p.deleted = 0
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByName(string $name): array
    {
        if (strlen($name) < 3) {
            return ['success' => false, 'errors' => ['El nombre debe tener mínimo 3 caracteres']];
        }

        $stmt = $this->db->prepare("
            SELECT p.idProduct, p.name, p.brand, c.name AS category, p.price, p.quantity, p.description
            FROM Product p
            INNER JOIN Category c ON p.category_id = c.idCategory
            WHERE p.name LIKE :name
              AND p.deleted = 0
              AND p.quantity > 0
        ");
        $stmt->execute([':name' => "%$name%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByCategory(string $category): array
    {
        $stmt = $this->db->prepare("
            SELECT p.idProduct, p.name, p.brand, c.name AS category, p.price, p.quantity, p.description
            FROM Product p
            INNER JOIN Category c ON p.category_id = c.idCategory
            WHERE c.name = :category
              AND c.state = 'Activo'
              AND p.deleted = 0
        ");
        $stmt->execute([':category' => $category]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows ?: ['success' => false, 'message' => 'No hay productos asociados a la categoría'];
    }

    public function findByPriceRange(float $min, float $max): array
    {
        $stmt = $this->db->prepare("
            SELECT p.idProduct, p.name, p.brand, c.name AS category, p.price, p.quantity, p.description
            FROM Product p
            INNER JOIN Category c ON p.category_id = c.idCategory
            WHERE p.price BETWEEN :min AND :max
              AND p.deleted = 0
        ");
        $stmt->execute([':min' => $min, ':max' => $max]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(int $idProduct, array $data): array
    {
        $errors = $this->validator->validate($data, $idProduct);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $fields = [
            'name'        => $data['name'],
            'brand'       => $data['brand'],
            'category_id' => $data['category_id'],
            'price'       => $data['price'],
            'quantity'    => $data['quantity'],
            'description' => $data['description'] ?? null
        ];

        $setParts = [];
        foreach ($fields as $key => $value) {
            $setParts[] = "$key = :$key";
        }
        $setString = implode(', ', $setParts);

        $stmt = $this->db->prepare("UPDATE Product SET $setString WHERE idProduct = :id");
        $fields['id'] = $idProduct;

        $success = $stmt->execute($fields);

        return $success
            ? ['success' => true, 'message' => 'Producto actualizado correctamente.']
            : ['success' => false, 'errors' => ['Error al actualizar el producto.']];
    }

    public function softDelete(int $idProduct): bool
    {
        $stmt = $this->db->prepare("UPDATE Product SET deleted = 1 WHERE idProduct = :id");
        return $stmt->execute([':id' => $idProduct]);
    }

    public function hardDelete(int $idProduct): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Product WHERE idProduct = :id");
        return $stmt->execute([':id' => $idProduct]);
    }
}
