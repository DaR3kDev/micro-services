<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// Crear servidor central
$server = new \soap_server();

// Cargar servicios independientes
require_once __DIR__ . '/services/UsersSOAP.php';
// require_once __DIR__ . '/src/products/soap/ProductsSOAP.php'; // futuro

// Procesar solicitud
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
