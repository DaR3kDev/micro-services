<?php

namespace App\products\services;

use App\products\models\Product;
use App\utils\ArrayHelper;

class ProductService
{
    private readonly Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function addProduct($product): array
    {
        $productArray = ArrayHelper::toArray($product->product ?? $product);
        return $this->productModel->save($productArray);
    }

    public function updateProduct(int $id, $product): array
    {
        $productArray = ArrayHelper::toArray($product->product ?? $product);
        return $this->productModel->update($id, $productArray);
    }

    public function listProducts(): array
    {
        return $this->productModel->getAll();
    }

    public function findProductByName(string $name): array
    {
        return $this->productModel->findByName($name);
    }

    public function findProductsByCategory(string $category): array
    {
        return $this->productModel->findByCategory($category);
    }

    public function findProductsByPriceRange(float $min, float $max): array
    {
        return $this->productModel->findByPriceRange($min, $max);
    }

    public function softDeleteProduct(int $id): bool
    {
        return $this->productModel->softDelete($id);
    }

    public function hardDeleteProduct(int $id): bool
    {
        return $this->productModel->hardDelete($id);
    }
}
