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
        $productList = new ProductList(['A','C','C']);
        $productList->addProduct('B');

        $this->assertEquals(['A' => 1,'B' => 1,'C' => 2], $productList->getlist());
    }

    public function testDeleteProduct()
    {
        $productList = new ProductList(['A','C','C']);
        $productList->deleteProduct('C');

        $this->assertEquals(['A' => 1,'C' => 1], $productList->getlist());
        
        $productList->deleteProduct('A');

        $this->assertEquals(['C' => 1], $productList->getlist());
    }

    public function testGetIntersectKeyList()
    {
        $productList = new ProductList(['A','C','C','D','E']);

        $this->assertEquals(['D','C'], $productList->getIntersectKeyArray(['J','D','M','C']));
        $this->assertEquals([], $productList->getIntersectKeyArray(['J','M']));
        $this->assertEquals([], $productList->getIntersectKeyArray([]));
    }

    public function testSortByProductPrice()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['C','A','C','B','A']);

        $this->assertEquals(['C','A','B'], $productList->getKeyList());

        $productList->sortByProductPrice($productCatalog);
        
        $this->assertEquals(['B','A','C'], $productList->getKeyList());
    }

    public function testGetDiffListByKeys()
    {
        $productList = new ProductList(['C','A','C','B','A']);

        $this->assertEquals(['A' => 2], $productList->getDiffListByKeys(['B','C']));
        $this->assertEquals(['A' => 2,'B' => 1], $productList->getDiffListByKeys(['C']));
        $this->assertEquals(['C' => 2,'A' => 2,'B' => 1], $productList->getDiffListByKeys([]));
    }

    public function testCalculateTotalQuantity()
    {
        $productList = new ProductList(['C','A','C','B','A','A']);

        $this->assertEquals(6, $productList->getProductTotalQuantity());
    }

    public function testToArray()
    {
        $productList = new ProductList(['C','A','C','B','A','A']);

        $this->assertEquals(['C','C','A','A','A','B'], $productList->toArray());
    }
}
