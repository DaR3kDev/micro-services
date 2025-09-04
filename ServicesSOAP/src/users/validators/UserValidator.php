<?php

namespace App\users\validators;

use App\users\models\Users;

class UserValidator
{
    private Users $userModel;

    public function __construct(Users $userModel)
    {
        $this->userModel = $userModel;
    }

    public function validate(array $data): array
    {
        $rules = [
            'first_name' => fn($v) => empty($v) ? "El nombre es obligatorio." : null,
            'last_name'  => fn($v) => empty($v) ? "El apellido es obligatorio." : null,
            'email'      => fn($v) => $this->validateEmail($v),
            'password'   => fn($v) => $this->validatePassword($v),
            'rol'        => fn($v) => $this->validateRole($v),
        ];

        $errors = [];

        foreach ($rules as $field => $validator) {
            $error = $validator($data[$field] ?? null);
            if ($error) $errors[] = $error;
        }

        $errors = array_merge($errors, $this->checkDuplicateEmail($data['email'] ?? null));

        return $errors;
    }

    private function validateEmail(?string $email): ?string
    {
        if (!$email) return "El email es obligatorio.";
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? null : "El email no es válido.";
    }

    private function validatePassword(?string $password): ?string
    {
        if (!$password) return "La contraseña es obligatoria.";
        return strlen($password) < 6 ? "La contraseña debe tener al menos 6 caracteres." : null;
    }

    private function validateRole(?string $role): ?string
    {
        return $role && !in_array($role, ['admin', 'client'])
            ? "El rol debe ser 'admin' o 'client'"
            : null;
    }

    private function checkDuplicateEmail(?string $email): array
    {
        return $email && $this->userModel->findByEmail($email)
            ? ["El email ya está registrado."]
            : [];
    }
}
