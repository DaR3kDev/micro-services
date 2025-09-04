<?php

namespace App\products\validators;

class ProductValidator
{
    public function validate(array $data, ?int $idProduct = null): array
    {
        $errors = [];

        // Validar nombre
        if (empty($data['name']) || strlen(trim($data['name'])) < 3) {
            $errors[] = "El nombre del producto debe tener al menos 3 caracteres.";
        }

        // Validar marca
        if (empty($data['brand'])) {
            $errors[] = "La marca es obligatoria.";
        }

        // Validar categoría
        if (empty($data['category_id']) || !is_numeric($data['category_id'])) {
            $errors[] = "La categoría es obligatoria y debe ser un número válido.";
        }

        // Validar precio
        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            $errors[] = "El precio debe ser un número mayor a 0.";
        }

        // Validar cantidad
        if (!isset($data['quantity']) || !is_numeric($data['quantity']) || $data['quantity'] < 0) {
            $errors[] = "La cantidad debe ser un número mayor o igual a 0.";
        }

        return $errors;
    }
}
