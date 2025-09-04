<?php
require_once __DIR__ . '/../../users/services/UsersService.php';
require_once __DIR__ . '/../../../utils/ArrayHelper.php';

use App\users\services\UsersService;

function registerUserServices($server, $namespace)
{
    // ============================
    // Definición de tipos complejos
    // ============================
    $server->wsdl->addComplexType('UserInput', 'complexType', 'struct', 'all', '', [
        'first_name' => ['name' => 'first_name', 'type' => 'xsd:string'],
        'last_name'  => ['name' => 'last_name',  'type' => 'xsd:string'],
        'email'      => ['name' => 'email',      'type' => 'xsd:string'],
        'password'   => ['name' => 'password',   'type' => 'xsd:string'],
        'rol'        => ['name' => 'rol',        'type' => 'xsd:string']
    ]);

    $server->wsdl->addComplexType('LoginInput', 'complexType', 'struct', 'all', '', [
        'email'    => ['name' => 'email',    'type' => 'xsd:string'],
        'password' => ['name' => 'password', 'type' => 'xsd:string']
    ]);

    $server->wsdl->addComplexType('UpdateUserInput', 'complexType', 'struct', 'all', '', [
        'id'         => ['name' => 'id',        'type' => 'xsd:int'],
        'first_name' => ['name' => 'first_name', 'type' => 'xsd:string'],
        'last_name'  => ['name' => 'last_name', 'type' => 'xsd:string'],
        'email'      => ['name' => 'email',     'type' => 'xsd:string'],
        'password'   => ['name' => 'password',  'type' => 'xsd:string'],
        'rol'        => ['name' => 'rol',       'type' => 'xsd:string']
    ]);

    // ============================
    // Registro de métodos
    // ============================
    $server->register('addUserSOAP',         ['user' => 'tns:UserInput'],       ['return' => 'xsd:string'], $namespace);
    $server->register('updateUserSOAP',      ['data' => 'tns:UpdateUserInput'], ['return' => 'xsd:string'], $namespace);
    $server->register('listUsersSOAP',       [],                               ['return' => 'xsd:string'], $namespace);
    $server->register('findUserByEmailSOAP', ['email' => 'xsd:string'],         ['return' => 'xsd:string'], $namespace);
    $server->register('loginUserSOAP',       ['data' => 'tns:LoginInput'],      ['return' => 'xsd:string'], $namespace);
    $server->register('deactivateUserSOAP',  ['id' => 'xsd:int'],               ['return' => 'xsd:string'], $namespace);
    $server->register('hardDeleteUserSOAP',  ['id' => 'xsd:int'],               ['return' => 'xsd:string'], $namespace);
}

// ============================
// Funciones SOAP
// ============================
function addUserSOAP($user)
{
    $service = new UsersService();
    $result = $service->addUser($user);

    return $result['success']
        ? "Usuario guardado correctamente"
        : "Error al registrar usuario: " . implode("; ", $result['errors'] ?? []);
}

function updateUserSOAP($data)
{
    $service = new UsersService();
    $id = is_array($data) ? ($data['id'] ?? null) : ($data->id ?? null);

    if (!$id) {
        return "Error: El ID del usuario es obligatorio";
    }

    $result = $service->updateUser($id, $data);

    return $result['success']
        ? "Usuario actualizado correctamente"
        : "Error al actualizar usuario: " . implode("; ", $result['errors'] ?? []);
}

function listUsersSOAP()
{
    $service = new UsersService();
    return json_encode($service->listUsers());
}

function findUserByEmailSOAP($email)
{
    $service = new UsersService();
    $user = $service->findUserByEmail($email);
    return $user ? json_encode($user) : "Error: Usuario no encontrado";
}

function loginUserSOAP($data)
{
    $service = new UsersService();
    $result = $service->loginUser($data);

    return $result['success']
        ? json_encode($result)
        : "Error: " . ($result['message'] ?? "Credenciales inválidas");
}

function deactivateUserSOAP($id)
{
    $service = new UsersService();
    return $service->deactivateUser($id)
        ? "Usuario desactivado correctamente"
        : "Error al desactivar usuario";
}

function hardDeleteUserSOAP($id)
{
    $service = new UsersService();
    return $service->hardDeleteUser($id)
        ? "Usuario eliminado permanentemente"
        : "Error al eliminar usuario permanentemente";
}
