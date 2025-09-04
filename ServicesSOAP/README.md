# 📌 ServicesSOAP

Proyecto **SOAP en PHP** para la gestión de **usuarios** y **productos**.

---

## 📂 Arquitectura del Proyecto

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
│   │   └── validators/
│   │       └── UserValidator.wsdl  # Validaciones
│   │
│   ├── products/                   # Módulo de productos
│   │   ├── models/
│   │   ├── services/
│   │   └── validators/
│   │
│   ├── soap/
│   │   └── server.php              # Servidor SOAP unificado
│   │
│   ├── utils/
│   │   └── ArrayHelper.php         # Funciones de ayuda
│   │
│   └── index.php                   # Punto de entrada
│
│── vendor/                         # Dependencias de Composer
│── composer.json                   # Configuración de Composer
│── docker-compose.yml              # Configuración de Docker
│── script.sql                      # Script de base de datos
│── .env                            # Variables de entorno
```

---

## ⚙️ Instalación

1. **Clonar el repositorio**:

   ```bash
   git clone <URL_DEL_REPOSITORIO> ServicesSOAP
   cd ServicesSOAP
   ```

2. **Instalar dependencias con Composer**:

   ```bash
   composer install
   ```

3. **Configurar la base de datos** en:

   ```
   src/config/database.php
   ```

---

## ▶️ Ejecución

Iniciar servidor con PHP built-in:

```bash
php -S localhost:8000 -t src/
```

Acceder al **WSDL de usuarios**:

```
http://localhost:8000/?wsdl
```

---

## 📡 Endpoints SOAP

### 👤 Usuarios

#### ➕ Crear Usuario

```xml
<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xmlns:xsd="http://www.w3.org/2001/XMLSchema"
   xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
   xmlns:user="UserServiceSOAP">
   <soapenv:Header/>
   <soapenv:Body>
      <user:addUserSOAP soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
         <user xsi:type="user:UserInput">
            <first_name xsi:type="xsd:string">Kevin</first_name>
            <last_name xsi:type="xsd:string">Lopez</last_name>
            <email xsi:type="xsd:string">kevin@example.com</email>
            <password xsi:type="xsd:string">miPasswordSegura123</password>
            <rol xsi:type="xsd:string">client</rol>
         </user>
      </user:addUserSOAP>
   </soapenv:Body>
</soapenv:Envelope>
```

---

## 🛠️ Debug y Logs

### 🔹 Servidor (`server.php`)

Guardar solicitudes SOAP en un log:

```php
file_put_contents(__DIR__ . '/soap_request.log', $content);
```

### 🔹 Servicios (`UsuarioService.php`)

Guardar datos para depuración:

```php
file_put_contents(__DIR__ . '/debug.log', print_r($data, true), FILE_APPEND);
```
