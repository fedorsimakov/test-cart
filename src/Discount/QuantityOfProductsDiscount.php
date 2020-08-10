<?php

namespace Fedorsimakov\Test\Cart\Discount;

use Fedorsimakov\Test\Cart\Product\ProductList;

class QuantityOfProductsDiscount implements DiscountInterface
{
    private $productNotDiscountList = [];
    private $quantityOfProducts;
    private $amountOfDiscount;

    public function __construct(array $productNotDiscountList, $quantityOfProducts, $amountOfDiscount)
    {
        $this->productNotDiscountList = $productNotDiscountList;
        $this->quantityOfProducts = $quantityOfProducts;
        $this->amountOfDiscount = $amountOfDiscount;
    }

    public function isApplyDiscount(ProductList $productList): bool
    {
        if ($productList->getProductTotalQuantity($this->productNotDiscountList) >= $this->quantityOfProducts) {
            return true;
        }
        return false;
    }
    
    public function getProductArrayForApplyDiscount(ProductList $productList): array
    {
        if ($this->isApplyDiscount($productList)) {
            $diffProductList = $productList->getDiffListByKeys($this->productNotDiscountList);
            $diffProductArray = array_reduce(
                array_keys($diffProductList),
                function ($acc, $item) use ($diffProductList) {
                    $acc = array_merge($acc, array_fill(0, $diffProductList[$item], $item));
                    return $acc;
                },
                []
            );
            return array_slice($diffProductArray, 0, $this->quantityOfProducts);
        }
        return [];
    }

    public function getAmountOfDiscountTotal(ProductList $productList): float
    {
        $productArrayForApplyDiscount = $this->getProductArrayForApplyDiscount($productList);
        $totalPrice = array_reduce($productArrayForApplyDiscount, function ($acc, $item) use ($productList) {
            return $acc += $productList->getProductCatalog()->getProductByName($item)->getPrice();
        }, 0);
        return $totalPrice * ($this->amountOfDiscount / 100);
    }
}
