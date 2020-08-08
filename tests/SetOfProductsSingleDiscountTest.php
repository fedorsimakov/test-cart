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
        $productList = new ProductList(['A','B','C','C','D']);
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
        $productA = new Product('A', 3.25);
        $productB = new Product('B', 2.47);
        $productC = new Product('C', 7.23);

        $productCatalog = new ProductCatalog([$productA, $productB, $productC]);

        $productList = new ProductList(['C','A','C','B','A']);

        $setOfProductsSingleDiscount = new SetOfProductsSingleDiscount('A', ['B','C'], 10);

        $this->assertEquals(
            0.247,
            $setOfProductsSingleDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );

        $productList = new ProductList(['B','B','B','B','D']);
      
        $this->assertEquals(
            0,
            $setOfProductsSingleDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );

        $productList = new ProductList(['A','A','A']);
      
        $this->assertEquals(
            0,
            $setOfProductsSingleDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );
    }
}
