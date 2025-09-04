
# Micro-Services

Proyecto de microservicios que combina **REST** y **SOAP** para la gestión de **usuarios**, **productos**, **carritos de compras**, **pagos** y **pedidos**.

Este proyecto está pensado para un **e-commerce modular**, donde cada servicio puede escalar de forma independiente.
---

## División de Microservicios

### Microservicios SOAP

1. **Servicio de Catálogo de Productos**

   * **Funcionalidad:** Proporciona la lista de productos disponibles y sus detalles. Maneja búsquedas y filtrado de productos.
   * **Ejemplo WSDL:** `http://localhost:8000/src/products/wsdl/product.wsdl`

2. **Servicio de Gestión de Usuarios**

   * **Funcionalidad:** Administra la autenticación, autorización y gestión de la información del usuario.
   * **Ejemplo WSDL:** `http://localhost:8000/src/users/wsdl/users.wsdl`
---


## Flujo de Trabajo

1. El **usuario navega por el catálogo de productos**, gestionado por el **Servicio de Catálogo de Productos (SOAP)**.
2. El usuario **agrega productos a su carrito**, que es manejado por el **Servicio de Carrito de Compras (REST)**. Este servicio puede consultar los detalles de los productos vía SOAP.
3. Al proceder al pago, el **Servicio de Carrito de Compras** interactúa con el **Servicio de Procesamiento de Pagos (REST)** para validar y procesar la transacción.
4. Tras el pago, el **Servicio de Carrito de Compras** envía la información al **Servicio de Gestión de Pedidos (REST)** para crear el pedido.
5. El **Servicio de Gestión de Pedidos** actualiza el estado del pedido y confirma la operación, notificando al usuario sobre el éxito de la transacción.

---

## Arquitectura del Proyecto

```
Micro-Services/
│── src/
│   ├── config/
│   │   └── database.php
│   │
│   ├── users/                     # Módulo de usuarios
│   │   ├── models/
│   │   ├── services/
│   │   └── wsdl/ 
│   │
│   ├── products/                  # Módulo de productos
│   │   ├── models/
│   │   ├── services/
│   │   └── wsdl/
│   │
│   ├── cart/                      # Módulo REST de carrito
│   │   └── endpoints.php
│   │
│   ├── payment/                   # Módulo REST de pagos
│   │   └── endpoints.php
│   │
│   ├── orders/                    # Módulo REST de pedidos
│   │   └── endpoints.php
│   │
│   ├── server.php                 # Servidor SOAP unificado
│   └── client.php                 # Cliente SOAP unificado

```

---

## Requisitos

* PHP 8 o superior
* Composer
* Servidor web o PHP built-in (`php -S`)
* Base de datos MySQL

---

## Instalación

1. Clonar el repositorio:

```bash
git clone <URL_DEL_REPOSITORIO> Micro-Services
cd Micro-Services
```

2. Instalar dependencias:

```bash
composer install
```

3. Configurar base de datos en `src/config/database.php`.

4. Ejecutar el script SQL:

```bash
mysql -u <usuario> -p < script.SQL
```
