<?php

namespace Fedorsimakov\Test\Cart\Tests;

use PHPUnit\Framework\TestCase;
use Fedorsimakov\Test\Cart\Product\Product;
use Fedorsimakov\Test\Cart\Product\ProductCatalog;
use Fedorsimakov\Test\Cart\Product\ProductList;

class ProductListTest extends TestCase
{
    public function testAddProduct()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['A','C','C'], $productCatalog);
        $productList->addProduct('B');

        $this->assertEquals(['A' => 1,'B' => 1,'C' => 2], $productList->getlist());
    }

    public function testDeleteProduct()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['A','C','C'], $productCatalog);
        $productList->deleteProduct('C');

        $this->assertEquals(['A' => 1,'C' => 1], $productList->getlist());
        
        $productList->deleteProduct('A');

        $this->assertEquals(['C' => 1], $productList->getlist());
    }

    public function testGetIntersectKeyList()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23),
            new Product('D', 22.23),
            new Product('E', 4.23),
            new Product('F', 9.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['A','C','C','D','E'], $productCatalog);

        $this->assertEquals(['D','C'], $productList->getIntersectKeyArray(['B','D','F','C']));
        $this->assertEquals([], $productList->getIntersectKeyArray(['B','F']));
        $this->assertEquals([], $productList->getIntersectKeyArray([]));
    }

    public function testSortByProductPrice()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23),
            new Product('D', 22.23),
            new Product('E', 4.23),
            new Product('F', 9.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['C','A','C','B','A'], $productCatalog);

        $this->assertEquals(['C','A','B'], $productList->getKeyList());

        $productList->sortByProductPrice();
        
        $this->assertEquals(['B','A','C'], $productList->getKeyList());
    }

    public function testGetDiffListByKeys()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['C','A','C','B','A'], $productCatalog);

        $this->assertEquals(['A' => 2], $productList->getDiffListByKeys(['B','C']));
        $this->assertEquals(['A' => 2,'B' => 1], $productList->getDiffListByKeys(['C']));
        $this->assertEquals(['C' => 2,'A' => 2,'B' => 1], $productList->getDiffListByKeys([]));
    }

    public function testCalculateTotalQuantity()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['C','A','C','B','A','A'], $productCatalog);

        $this->assertEquals(6, $productList->getProductTotalQuantity());
    }

    public function testToArray()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23)
        ];

        $productCatalog = new ProductCatalog($products);
        
        $productList = new ProductList(['C','A','C','B','A','A'], $productCatalog);

        $this->assertEquals(['C','C','A','A','A','B'], $productList->toArray());
    }
}
