<?php

namespace Fedorsimakov\Test\Cart;

use Fedorsimakov\Test\Cart\Product\ProductCatalog;
use Fedorsimakov\Test\Cart\Product\ProductList;
use Fedorsimakov\Test\Cart\Discount\DiscountInterface;

class Cart
{
    private $productCatalog;
    private $productList;
    private $productListWithoutApplyDiscount;

    private $discountArray = [
        'setOfProducts' => [],
        'quantityOfProducts' => []
    ];

    public function __construct(ProductCatalog $productCatalog)
    {
        $this->productCatalog = $productCatalog;
        $this->productList = new ProductList([], $productCatalog);
        $this->productListWithoutApplyDiscount = new ProductList([], $productCatalog);
    }

    public function addDiscounts(array $setOfProductsDiscounts, array $quantityOfProductsDiscounts)
    {
        $this->discountArray['setOfProducts'] = $setOfProductsDiscounts;
        $this->discountArray['quantityOfProducts'] = $quantityOfProductsDiscounts;
    }

    public function reloadProductLists()
    {
        $this->productListWithoutApplyDiscount = new ProductList($this->productList->toArray(), $this->productCatalog);
    }

    public function addProducts(array $productNames)
    {
        $this->productList->addProducts($productNames);
        $this->productList->sortByProductPrice();
        $this->reloadProductLists();
    }

    public function addProduct(string $productName)
    {
        $this->productList->addProduct($productName);
        $this->productList->sortByProductPrice();
        $this->reloadProductLists();
    }

    public function deleteProducts(array $productNames)
    {
        $this->productList->deleteProducts($productNames);
        $this->productList->sortByProductPrice();
        $this->reloadProductLists();
    }

    public function deleteProduct(string $productName)
    {
        $this->productList->deleteProduct($productName);
        $this->productList->sortByProductPrice();
        $this->reloadProductLists();
    }

    public function calculateDiscount(DiscountInterface $discount): float
    {
        $productArrayForApplyDiscount = $discount->getProductArrayForApplyDiscount($this->productListWithoutApplyDiscount);
        $result = $discount->getAmountOfDiscountTotal($this->productListWithoutApplyDiscount);

        $this->productListWithoutApplyDiscount->deleteProducts($productArrayForApplyDiscount);

        return $result;
    }

    public function calculateTotalDiscount(): float
    {
        $totalDiscount = 0;
        $discountArray = $this->discountArray;
        foreach ($discountArray['setOfProducts'] as $discount) {
            $currDiscount = $this->calculateDiscount($discount);
            $totalDiscount += $currDiscount;
        }
        foreach ($discountArray['quantityOfProducts'] as $discount) {
            $currDiscount = $this->calculateDiscount($discount);
            if (!empty($currDiscount)) {
                $totalDiscount += $currDiscount;
                break;
            }
        }
        $this->reloadProductLists();
        return $totalDiscount;
    }

    public function calculateTotalProductCost(): float
    {
        $productList = $this->productList->getList();

        return array_reduce(array_keys($productList), function ($acc, $item) use ($productList) {
            return $acc += $this->productCatalog->getProductByName($item)->getPrice() * $productList[$item];
        }, 0);
    }
}
