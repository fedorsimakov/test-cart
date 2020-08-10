<?php

namespace Fedorsimakov\Test\Cart\Tests;

use PHPUnit\Framework\TestCase;
use Fedorsimakov\Test\Cart\Product\Product;
use Fedorsimakov\Test\Cart\Product\ProductCatalog;
use Fedorsimakov\Test\Cart\Product\ProductList;
use Fedorsimakov\Test\Cart\Discount\SetOfProductsManyDiscount;

class SetOfProductsManyDiscountTest extends TestCase
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

        $productList = new ProductList(['A','B','C','C','D','A','B'], $productCatalog);
        $setOfProductsManyDiscount = new SetOfProductsManyDiscount(['A','C','B'], 10);

        $this->assertEquals(
            ['A','A','C','C','B','B'],
            $setOfProductsManyDiscount->getProductArrayForApplyDiscount($productList)
        );

        $setOfProductsManyDiscount = new SetOfProductsManyDiscount(['A', 'J'], 10);
        
        $this->assertEquals(
            [],
            $setOfProductsManyDiscount->getProductArrayForApplyDiscount($productList)
        );
    }

    public function testGetAmountOfDiscountTotal()
    {
        $products = [
            new Product('A', 3.25),
            new Product('B', 2.47),
            new Product('C', 7.23)
        ];

        $productCatalog = new ProductCatalog($products);

        $productList = new ProductList(['C','A','C','B','A'], $productCatalog);

        $setOfProductsManyDiscount = new SetOfProductsManyDiscount(['A', 'C'], 10);

        $this->assertEquals(
            2.096,
            $setOfProductsManyDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );

        $productList = new ProductList(['B','B','B','B','A'], $productCatalog);
      
        $this->assertEquals(
            0,
            $setOfProductsManyDiscount->getAmountOfDiscountTotal($productList, $productCatalog)
        );
    }
}
