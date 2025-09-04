<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$namespace = "GlobalServiceSOAP";
$server = new \soap_server();
$server->configureWSDL("GlobalService", $namespace);

// Registrar servicios
require_once __DIR__ . '/services/UsersSOAP.php';
require_once __DIR__ . '/services/ProductsSOAP.php';

// Llamar funciones que registran cada grupo de métodos
registerUserServices($server, $namespace);
registerProductServices($server, $namespace);

// Procesar solicitud
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
