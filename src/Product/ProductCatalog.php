<?php

namespace Fedorsimakov\Test\Cart\Product;

class ProductCatalog
{
    private $catalog = [];

    public function __construct(array $products)
    {
        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    public function addProduct(Product $product)
    {
        $this->catalog[$product->getName()] = $product;
    }

    public function getCatalog(): array
    {
        return $this->catalog;
    }

    public function getKeyCatalog(): array
    {
        return array_keys($this->catalog);
    }

    public function getProductByName(string $productName): ?Product
    {
        if (array_key_exists($productName, $this->getCatalog())) {
            return $this->getCatalog()[$productName];
        }
        return null;
    }
}
