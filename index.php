<?php

require_once __DIR__ . '/vendor/autoload.php';

use Fedorsimakov\Test\Cart\Product\Product;
use Fedorsimakov\Test\Cart\Product\ProductCatalog;
use Fedorsimakov\Test\Cart\Discount\SetOfProductsManyDiscount;
use Fedorsimakov\Test\Cart\Discount\SetOfProductsSingleDiscount;
use Fedorsimakov\Test\Cart\Discount\QuantityOfProductsDiscount;
use Fedorsimakov\Test\Cart\Cart;

$products = [
    new Product('A', 3.25),
    new Product('B', 2.47),
    new Product('C', 7.23),
    new Product('D', 9.23),
    new Product('E', 34.23),
    new Product('F', 12.23),
    new Product('G', 25.53),
    new Product('H', 5.23),
    new Product('I', 17.23),
    new Product('J', 44.13),
    new Product('K', 89.23),
    new Product('L', 37.23),
    new Product('M', 18.23)
];

$productCatalog = new ProductCatalog($products);

$setOfProductsDiscountRules = [
    new SetOfProductsManyDiscount(['A','B'], 10),
    new SetOfProductsManyDiscount(['D','E'], 5),
    new SetOfProductsManyDiscount(['E','F','G'], 5),
    new SetOfProductsSingleDiscount('A', ['K','L','M'], 5),
];

$quantityOfProductsDiscountRules = [
    new QuantityOfProductsDiscount(['A','C'], 5, 20),
    new QuantityOfProductsDiscount(['A','C'], 4, 10),
    new QuantityOfProductsDiscount(['A','C'], 3, 5)
];

$cart = new Cart($productCatalog);
$cart->addProducts(['A','C','A','B','K','M','I','D','F','M','K']);
$cart->addDiscounts($setOfProductsDiscountRules, $quantityOfProductsDiscountRules);

$totalProduct = round($cart->calculateTotalProduct(), 2);
$totalDiscout = round($cart->calculateTotalDiscount(), 2);
$totalCost = round($cart->calculateTotalCost(), 2);

print_r("Общая стоимость товаров: {$totalProduct}\n");
print_r("Скидка: {$totalDiscout}\n");
print_r("Общая стоимость товаров с учетом скидки: {$totalCost}\n");