<?php

namespace Fedorsimakov\Test\Cart\Discount;

use Fedorsimakov\Test\Cart\Product\ProductList;

class SetOfProductsManyDiscount implements DiscountInterface
{
    private $productDiscountList = [];
    private $amountOfDiscount;

    public function __construct($productNames, $amountOfDiscount)
    {
        $this->productDiscountList = $productNames;
        $this->amountOfDiscount = $amountOfDiscount;
    }

    public function isApplyDiscount(ProductList $productList): bool
    {
        foreach ($productList as $productName) {
            if (!$productList->isProductExist($productName)) {
                return false;
            }
        }
        return true;
    }
    
    public function getProductArrayForApplyDiscount(ProductList $productList): array
    {
        $manyCount = $this->getManyCount($productList);
        if ($this->isApplyDiscount($productList)) {
            return array_reduce($this->productDiscountList, function ($acc, $item) use ($manyCount) {
                return array_merge($acc, array_fill(0, $manyCount, $item));
            }, []);
        }
        return [];
    }

    public function getManyCount(ProductList $productList): int
    {
        $manyCountArray = array_map(function ($item) use ($productList) {
            return $productList->getProductQuantityByName($item);
        }, $this->productDiscountList);
        return min($manyCountArray);
    }

    public function getAmountOfDiscountTotal(ProductList $productList): float
    {
        $manyCount = $this->getManyCount($productList);
        $totalPrice = array_reduce($this->productDiscountList, function ($acc, $item) use ($productList) {
            return $acc += $productList->getProductCatalog()->getProductByName($item)->getPrice();
        }, 0);
        return $totalPrice * ($this->amountOfDiscount / 100) * $manyCount;
    }
}
