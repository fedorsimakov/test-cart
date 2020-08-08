<?php

namespace Fedorsimakov\Test\Cart\Tests;

use PHPUnit\Framework\TestCase;
use Fedorsimakov\Test\Cart\Product\Product;

class ProductTest extends TestCase
{
    public function testGetName()
    {
        $name = 'A';
        $price = 6.42;
        $product = new Product($name, $price);

        $this->assertEquals($name, $product->getName());
    }

    public function testGetPrice()
    {
        $name = 'B';
        $price = 6.42;
        $product = new Product($name, $price);

        $this->assertEquals($price, $product->getPrice());
    }
}
