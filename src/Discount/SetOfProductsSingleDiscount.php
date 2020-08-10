<?php

namespace Fedorsimakov\Test\Cart\Discount;

use Fedorsimakov\Test\Cart\Product\ProductList;

class SetOfProductsSingleDiscount implements DiscountInterface
{
    private $productDiscountList = [];
    private $amountOfDiscount;

    public function __construct(string $firstProductName, array $secondProductNames, $amountOfDiscount)
    {
        $this->productDiscountList = [
            0 => $firstProductName,
            1 => $secondProductNames
        ];
        $this->amountOfDiscount = $amountOfDiscount;
    }

    public function isApplyDiscount(ProductList $productList): bool
    {
        $isFirstProductExist = $productList->isProductExist($this->productDiscountList[0]);
        $isSecondProductsExist = !empty($productList->getIntersectKeyArray($this->productDiscountList[1]));
        
        return $isFirstProductExist && $isSecondProductsExist;
    }
    
    public function getProductArrayForApplyDiscount(ProductList $productList): array
    {
        if ($this->isApplyDiscount($productList)) {
            return [
                $this->productDiscountList[0],
                $productList->getIntersectKeyArray($this->productDiscountList[1])[0]
            ];
        }

        return [];
    }

    public function getAmountOfDiscountTotal(ProductList $productList): float
    {
        $productArrayForApplyDiscount = $this->getProductArrayForApplyDiscount($productList);
        if (!empty($productArrayForApplyDiscount)) {
            $productName = $productArrayForApplyDiscount[1];
            $productPrice = $productList->getProductCatalog()->getProductByName($productName)->getPrice();
            return $productPrice * ($this->amountOfDiscount / 100);
        }
        return 0;
    }
}
