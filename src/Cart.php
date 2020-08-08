<?php

namespace Fedorsimakov\Test\Cart;

use Fedorsimakov\Test\Cart\Product\ProductList;
use Fedorsimakov\Test\Cart\Discount\DiscountInterface;
use Fedorsimakov\Test\Cart\Product\ProductCatalog;

class Cart
{
    private $productCatalog = [];

    private $productList = [];
    private $productWithoutApplyDiscount = [];
    private $productWithApplyDiscount = [];

    private $discountList = [
        'setOfProducts' => [],
        'quantityOfProducts' => []
    ];

    public function __construct(ProductCatalog $productCatalog)
    {
        $this->productCatalog = $productCatalog;

        $this->productList = new ProductList([]);
        $this->productWithoutApplyDiscount = new ProductList([]);
        $this->productWithApplyDiscount = new ProductList([]);
    }

    public function addDiscounts(array $setOfProductsDiscounts, array $quantityOfProductsDiscounts)
    {
        $this->discountList['setOfProducts'] = $setOfProductsDiscounts;
        $this->discountList['quantityOfProducts'] = $quantityOfProductsDiscounts;
    }

    public function reloadProductLists()
    {
        $this->productWithoutApplyDiscount = new ProductList($this->productList->toArray());
        $this->productWithApplyDiscount = new ProductList([]);
    }

    public function addProducts(array $productNames)
    {
        $this->productList->addProducts($productNames);
        $this->productList->sortByProductPrice($this->productCatalog);
        $this->reloadProductLists();
    }

    public function addProduct(string $productName)
    {
        $this->productList->addProduct($productName);
        $this->productList->sortByProductPrice($this->productCatalog);
        $this->reloadProductLists();
    }

    public function deleteProducts(array $productNames)
    {
        $this->productList->deleteProducts($productNames);
        $this->productList->sortByProductPrice($this->productCatalog);
        $this->reloadProductLists();
    }

    public function deleteProduct(string $productName)
    {
        $this->productList->deleteProduct($productName);
        $this->productList->sortByProductPrice($this->productCatalog);
        $this->reloadProductLists();
    }

    public function calculateDiscount(DiscountInterface $discount): float
    {
        $productArrayForApplyDiscount = $discount->getProductArrayForApplyDiscount($this->productWithoutApplyDiscount);
        $result = $discount->getAmountOfDiscountTotal($this->productWithoutApplyDiscount, $this->productCatalog);

        $this->productWithApplyDiscount->addProducts($productArrayForApplyDiscount);
        $this->productWithoutApplyDiscount->deleteProducts($productArrayForApplyDiscount);

        return $result;
    }

    public function calculateTotalDiscount(): float
    {
        $totalDiscount = 0;
        $discountList = $this->discountList;
        foreach ($discountList['setOfProducts'] as $discount) {
            $currDiscount = $this->calculateDiscount($discount);
            $totalDiscount += $currDiscount;
        }
        foreach ($discountList['quantityOfProducts'] as $discount) {
            $currDiscount = $this->calculateDiscount($discount);
            if (!empty($currDiscount)) {
                $totalDiscount += $currDiscount;
                break;
            }
        }
        $this->reloadProductLists();
        return $totalDiscount;
    }

    public function calculateTotalProduct(): float
    {
        $productList = $this->productList->getList();

        return array_reduce(array_keys($productList), function ($acc, $item) use ($productList) {
            return $acc += $this->productCatalog->getProductByName($item)->getPrice() * $productList[$item];
        }, 0);
    }

    public function calculateTotalCost(): float
    {
        return $this->calculateTotalProduct() - $this->calculateTotalDiscount();
    }
}
