<?php
require_once __DIR__ . "/services/UserServices.php";

use App\services\UsersService;

$wsdl = __DIR__ . "/wsdl/users.wsdl";

$options = [
    "soap_version" => SOAP_1_2,
    "cache_wsdl" => WSDL_CACHE_NONE,
    "uri" => "http://localhost:8000/"
];

$server = new SoapServer($wsdl, $options);
$server->setClass(UsersService::class);
$server->handle();