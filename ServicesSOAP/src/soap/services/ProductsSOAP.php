<?php
require_once __DIR__ . '/../../products/services/ProductService.php';
require_once __DIR__ . '/../../../utils/ArrayHelper.php';

use App\products\services\ProductService;

function registerProductServices($server, $namespace)
{
    // ============================
    // Definición de tipos complejos
    // ============================
    $server->wsdl->addComplexType('ProductInput', 'complexType', 'struct', 'all', '', [
        'name'        => ['name' => 'name',        'type' => 'xsd:string'],
        'brand'       => ['name' => 'brand',       'type' => 'xsd:string'],
        'category_id' => ['name' => 'category_id', 'type' => 'xsd:int'],
        'price'       => ['name' => 'price',       'type' => 'xsd:float'],
        'quantity'    => ['name' => 'quantity',    'type' => 'xsd:int'],
        'description' => ['name' => 'description', 'type' => 'xsd:string']
    ]);

    $server->wsdl->addComplexType('UpdateProductInput', 'complexType', 'struct', 'all', '', [
        'id'          => ['name' => 'id',          'type' => 'xsd:int'],
        'name'        => ['name' => 'name',        'type' => 'xsd:string'],
        'brand'       => ['name' => 'brand',       'type' => 'xsd:string'],
        'category_id' => ['name' => 'category_id', 'type' => 'xsd:int'],
        'price'       => ['name' => 'price',       'type' => 'xsd:float'],
        'quantity'    => ['name' => 'quantity',    'type' => 'xsd:int'],
        'description' => ['name' => 'description', 'type' => 'xsd:string']
    ]);

    // ============================
    // Registro de métodos
    // ============================
    $server->register('addProductSOAP',              ['product' => 'tns:ProductInput'],        ['return' => 'xsd:string'], $namespace);
    $server->register('updateProductSOAP',           ['data' => 'tns:UpdateProductInput'],     ['return' => 'xsd:string'], $namespace);
    $server->register('listProductsSOAP',            [],                                      ['return' => 'xsd:string'], $namespace);
    $server->register('findProductByNameSOAP',       ['name' => 'xsd:string'],                 ['return' => 'xsd:string'], $namespace);
    $server->register('findProductsByCategorySOAP',  ['category' => 'xsd:string'],             ['return' => 'xsd:string'], $namespace);
    $server->register('findProductsByPriceRangeSOAP', ['min' => 'xsd:float', 'max' => 'xsd:float'], ['return' => 'xsd:string'], $namespace);
    $server->register('softDeleteProductSOAP',       ['id' => 'xsd:int'],                      ['return' => 'xsd:string'], $namespace);
    $server->register('hardDeleteProductSOAP',       ['id' => 'xsd:int'],                      ['return' => 'xsd:string'], $namespace);
}

// ============================
// Funciones SOAP
// ============================
function addProductSOAP($product)
{
    $service = new ProductService();
    $result = $service->addProduct($product);
    return $result['success'] ? "Producto guardado" : "Error: " . implode("; ", $result['errors'] ?? []);
}

function updateProductSOAP($data)
{
    $service = new ProductService();
    $id = is_array($data) ? ($data['id'] ?? null) : ($data->id ?? null);
    if (!$id) return "Error: ID obligatorio";

    $result = $service->updateProduct($id, $data);
    return $result['success'] ? "Producto actualizado" : "Error: " . implode("; ", $result['errors'] ?? []);
}

function listProductsSOAP()
{
    $service = new ProductService();
    return json_encode($service->listProducts());
}

function findProductByNameSOAP($name)
{
    $service = new ProductService();
    return json_encode($service->findProductByName($name));
}

function findProductsByCategorySOAP($category)
{
    $service = new ProductService();
    return json_encode($service->findProductsByCategory($category));
}

function findProductsByPriceRangeSOAP($min, $max)
{
    $service = new ProductService();
    return json_encode($service->findProductsByPriceRange($min, $max));
}

function softDeleteProductSOAP($id)
{
    $service = new ProductService();
    return $service->softDeleteProduct($id) ? "Producto eliminado lógicamente" : "Error al eliminar";
}

function hardDeleteProductSOAP($id)
{
    $service = new ProductService();
    return $service->hardDeleteProduct($id) ? "Producto eliminado permanentemente" : "Error al eliminar permanentemente";
}
