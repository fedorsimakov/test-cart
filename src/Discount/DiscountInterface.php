<?php

namespace Fedorsimakov\Test\Cart\Discount;

use Fedorsimakov\Test\Cart\Product\ProductList;

interface DiscountInterface
{
    public function isApplyDiscount(ProductList $productList);
    public function getProductArrayForApplyDiscount(ProductList $productList);
    public function getAmountOfDiscountTotal(ProductList $productList);
}
