<?php

namespace Fedorsimakov\Test\Cart\Tests;

use PHPUnit\Framework\TestCase;
use Fedorsimakov\Test\Cart\Product\Product;
use Fedorsimakov\Test\Cart\Product\ProductCatalog;
use Fedorsimakov\Test\Cart\Product\ProductList;
use Fedorsimakov\Test\Cart\Discount\QuantityOfProductsDiscount;

class QuantityOfProductsDiscountTest extends TestCase
{
    public function testGetProductListForApplyDiscount()
    {
        $productA = new Product('A', 4.25);
        $productB = new Product('B', 2.47);
        $productC = new Product('C', 3.42);
        $productD = new Product('D', 17.45);

        $productCatalog = new ProductCatalog([$productA, $productB, $productC, $productD]);
        
        $productList = new ProductList(['A','B','B','C','C','D','D'], $productCatalog);
        $productList->sortByProductPrice($productCatalog);

        $quantityOfProductsDiscount = new QuantityOfProductsDiscount(['A','C'], 3, 10);

        $this->assertEquals(
            ['B','B','D'],
            $quantityOfProductsDiscount->getProductArrayForApplyDiscount($productList)
        );

        $quantityOfProductsDiscount = new QuantityOfProductsDiscount([], 6, 10);
        
        $this->assertEquals(
            ['B','B','C','C','A','D'],
            $quantityOfProductsDiscount->getProductArrayForApplyDiscount($productList)
        );

        $quantityOfProductsDiscount = new QuantityOfProductsDiscount(['B','D'], 2, 10);

        $this->assertEquals(
            ['C','C'],
            $quantityOfProductsDiscount->getProductArrayForApplyDiscount($productList)
        );
    }

    public function testGetAmountOfDiscountTotal()
    {
        $productA = new Product('A', 3.25);
        $productB = new Product('B', 2.47);
        $productC = new Product('C', 7.23);

        $productCatalog = new ProductCatalog([$productA, $productB, $productC]);

        $productList = new ProductList(['C','A','C','B','A'], $productCatalog);
        $productList->sortByProductPrice($productCatalog);

        $quantityOfProductsDiscount = new QuantityOfProductsDiscount(['A'], 2, 10);

        $this->assertEquals(
            0.97,
            $quantityOfProductsDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );

        $quantityOfProductsDiscount = new QuantityOfProductsDiscount([], 4, 10);
      
        $this->assertEquals(
            1.62,
            $quantityOfProductsDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );

        $quantityOfProductsDiscount = new QuantityOfProductsDiscount([], 5, 10);
      
        $this->assertEquals(
            2.343,
            $quantityOfProductsDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );

        $productList = new ProductList([], $productCatalog);
        $productList->sortByProductPrice($productCatalog);
      
        $this->assertEquals(
            0,
            $quantityOfProductsDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );
    }
}
