<?php

namespace Fedorsimakov\Test\Cart\Tests;

use PHPUnit\Framework\TestCase;
use Fedorsimakov\Test\Cart\Product\Product;
use Fedorsimakov\Test\Cart\Product\ProductCatalog;
use Fedorsimakov\Test\Cart\Discount\SetOfProductsManyDiscount;
use Fedorsimakov\Test\Cart\Discount\SetOfProductsSingleDiscount;
use Fedorsimakov\Test\Cart\Discount\QuantityOfProductsDiscount;
use Fedorsimakov\Test\Cart\Cart;

class CartTest extends TestCase
{
    public function testCalculateDiscount()
    {
        $productA = new Product('A', 3.25);
        $productB = new Product('B', 2.47);
        $productC = new Product('C', 7.23);

        $productCatalog = new ProductCatalog([$productA, $productB, $productC]);

        $cart = new Cart($productCatalog);
        $cart->addProducts(['C','A','C','B','A']);
        $setOfProductsManyDiscount = new SetOfProductsManyDiscount(['A','C'], 10);

        $this->assertEquals(2.096, $cart->calculateDiscount($setOfProductsManyDiscount));

        $cart = new Cart($productCatalog);
        $cart->addProducts(['C','A','C','B','A']);
        $setOfProductsSingleDiscount = new SetOfProductsSingleDiscount('A', ['J','B'], 10);

        $this->assertEquals(0.247, $cart->calculateDiscount($setOfProductsSingleDiscount));
    }

    public function testCalculateTotalDiscout()
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

        $cart = new Cart($productCatalog);
        $cart->addProducts(['C','A','C','B','A','D','A','E','F','D']);
        $setOfProductsManyDiscount = new SetOfProductsManyDiscount(['A','C'], 10);
        $setOfProductsSingleDiscount = new SetOfProductsSingleDiscount('A', ['E','D','F'], 10);
        $quantityOfProductsDiscount = new QuantityOfProductsDiscount(['B'], 3, 5);
        $cart->addDiscounts(
            [$setOfProductsManyDiscount,$setOfProductsSingleDiscount],
            [$quantityOfProductsDiscount]
        );

        $this->assertEquals(5.2035, $cart->calculateTotalDiscount());
    }

    public function testCalculateTotalProductCost()
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

        $cart = new Cart($productCatalog);
        $cart->addProducts(['C','A','C','B','A','D','A','E','F','D']);

        $this->assertEquals(84.6, $cart->calculateTotalProductCost());
    }
}
