<?php

namespace Fedorsimakov\Test\Cart\Tests;

use PHPUnit\Framework\TestCase;
use Fedorsimakov\Test\Cart\Product\Product;
use Fedorsimakov\Test\Cart\Product\ProductCatalog;

class ProductCatalogTest extends TestCase
{
    public function testGetKeyCatalog()
    {
        $products = [
            new Product('A', 5.25),
            new Product('B', 7.47)
        ];

        $productCatalog = new ProductCatalog($products);

        $this->assertEquals(['A','B'], $productCatalog->getKeyCatalog());
    }

    public function testGetProductByName()
    {
        $productA = new Product('A', 5.25);
        $productB = new Product('B', 7.47);

        $productCatalog = new ProductCatalog([$productA, $productB]);

        $this->assertEquals($productB, $productCatalog->getProductByName('B'));
        $this->isNull($productCatalog->getProductByName('D'));
    }
}
