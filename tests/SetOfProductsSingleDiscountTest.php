<?php

namespace Fedorsimakov\Test\Cart\Tests;

use PHPUnit\Framework\TestCase;
use Fedorsimakov\Test\Cart\Product\Product;
use Fedorsimakov\Test\Cart\Product\ProductCatalog;
use Fedorsimakov\Test\Cart\Product\ProductList;
use Fedorsimakov\Test\Cart\Discount\SetOfProductsSingleDiscount;

class SetOfProductsSingleDiscountTest extends TestCase
{
    public function testGetProductListForApplyDiscount()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23),
            new Product('D', 14.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['A','B','C','C','D'], $productCatalog);
        $setOfProductsSingleDiscount = new SetOfProductsSingleDiscount('A', ['M','D','C','K'], 10);

        $this->assertEquals(
            ['A','D'],
            $setOfProductsSingleDiscount->getProductArrayForApplyDiscount($productList)
        );

        $setOfProductsSingleDiscount = new SetOfProductsSingleDiscount('A', ['J','K'], 10);
        
        $this->assertEquals(
            [],
            $setOfProductsSingleDiscount->getProductArrayForApplyDiscount($productList)
        );
    }

    public function testGetAmountOfDiscountTotal()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23),
            new Product('D', 14.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['C','A','C','B','A'], $productCatalog);

        $setOfProductsSingleDiscount = new SetOfProductsSingleDiscount('A', ['B','C'], 10);

        $this->assertEquals(
            0.247,
            $setOfProductsSingleDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );

        $productList = new ProductList(['B','B','B','B','D'], $productCatalog);
      
        $this->assertEquals(
            0,
            $setOfProductsSingleDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );

        $productList = new ProductList(['A','A','A'], $productCatalog);
      
        $this->assertEquals(
            0,
            $setOfProductsSingleDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );
    }
}
