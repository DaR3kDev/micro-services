# ServicesSOAP

Proyecto SOAP en PHP para la gestión de **usuarios** y **productos**.

---

## Arquitectura del Proyecto

```
ServicesSOAP/
│── src/
│   ├── config/
│   │   └── database.php            # Configuración de la base de datos
│   │
│   ├── users/                      # Módulo de usuarios
│   │   ├── models/
│   │   │   └── Users.php           # Modelo de usuario
│   │   ├── services/
│   │   │   └── UsuarioService.php  # Lógica de negocio para usuarios
│   │   └── wsdl/
│   │       └── usuarios.wsdl       # WSDL de usuarios
│   │
│   ├── products/                   # Módulo de productos
│   │   ├── models/
│   │   ├── services/
│   │   └── wsdl/
│   │
│   ├── server.php                  # Servidor SOAP unificado
│   └── client.php                  # Cliente SOAP unificado
│
│── vendor/                         # Dependencias de Composer
│── composer.json                    # Configuración de Composer
│── script.SQL                       # Script de base de datos
```

---

## Requisitos

- PHP 8 o superior
- Composer
- Servidor web o PHP built-in (`php -S`)
- Base de datos MySQL (o la que configures en `database.php`)

---

## Instalación

1. Clonar el repositorio:

```bash
git clone <URL_DEL_REPOSITORIO> ServicesSOAP
cd ServicesSOAP
```

2. Instalar dependencias con Composer:

```bash
composer install
```

3. Configurar la base de datos en `src/config/database.php`.

4. Ejecutar el script SQL:

```bash
mysql -u <usuario> -p < script.SQL
```

---

## Ejecución

Iniciar el servidor PHP:

```bash
php -S localhost:8000 -t .
```

Acceder al WSDL de usuarios:

```
http://localhost:8000/src/users/wsdl/usuarios.wsdl
```

Acceder al WSDL de productos:

```
http://localhost:8000/src/products/wsdl/productos.wsdl
```

---

## Debug y Logs

### Servidor (`server.php`)

Para depurar solicitudes SOAP:

```php
file_put_contents(__DIR__ . '/soap_request.log', $content);
```

### Services (`UsuarioService.php` o servicios de productos)

Para depurar datos en la lógica de negocio:

```php
file_put_contents(__DIR__ . '/debug.log', print_r($data, true), FILE_APPEND);
```

---

## Endpoints SOAP Disponibles

### Usuarios

#### Crear usuario

```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:user="http://localhost/wsdl/users">
   <soapenv:Header/>
   <soapenv:Body>
      <user:AddUserRequest>
         <user:user>
            <user:first_name>Kevin</user:first_name>
            <user:last_name>Lopez</user:last_name>
            <user:email>kevin@example.com</user:email>
            <user:password>12345</user:password>
            <user:rol>client</user:rol>
            <user:state>1</user:state>
         </user:user>
      </user:AddUserRequest>
   </soapenv:Body>
</soapenv:Envelope>
```