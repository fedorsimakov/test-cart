<?php

namespace Fedorsimakov\Test\Cart\Product;

use Fedorsimakov\Test\Cart\Product\ProductCatalog;

class ProductList
{
    private $productCatalog;
    private $list = [];

    public function __construct(array $productNames, ProductCatalog $productCatalog)
    {
        $this->productCatalog = $productCatalog;
        $this->addProducts($productNames);
    }

    public function addProducts(array $productNames)
    {
        foreach ($productNames as $productName) {
            $this->addProduct($productName);
        }
    }

    public function addProduct(string $productName)
    {
        if (empty($this->productCatalog->getProductByName($productName))) {
            throw new \Exception("Товара {$productName} нет в каталоге товаров");
        }
        if ($this->isProductExist($productName)) {
            $this->list[$productName] += 1;
        } else {
            $this->list[$productName] = 1;
        }
    }

    public function deleteProducts(array $productNames)
    {
        foreach ($productNames as $productName) {
            $this->deleteProduct($productName);
        }
    }

    public function deleteProduct(string $productName)
    {
        $list = $this->getList();
        if ($this->isProductExist($productName) && $list[$productName] > 1) {
            $list[$productName] -= 1;
        } else {
            unset($list[$productName]);
        }
        $this->list = $list;
    }

    public function isProductExist(string $productName): bool
    {
        return array_key_exists($productName, $this->getList());
    }

    public function getIntersectKeyArray(array $productNames): array
    {
        return array_values(array_filter($productNames, function ($productName) {
            return $this->isProductExist($productName);
        }));
    }

    public function getDiffListByKeys(array $exceptionProductNames): array
    {
        return array_filter($this->getList(), function ($key) use ($exceptionProductNames) {
            return in_array($key, $exceptionProductNames) ? false : true;
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getProductCatalog(): ProductCatalog
    {
        return $this->productCatalog;
    }

    public function getList(): array
    {
        return $this->list;
    }

    public function getKeyList(): array
    {
        return array_keys($this->list);
    }

    public function toArray()
    {
        $productList = $this->getList();
        return array_reduce($this->getKeyList(), function ($acc, $item) use ($productList) {
            return array_merge($acc, array_fill(0, $productList[$item], $item));
        }, []);
    }

    public function sortByProductPrice()
    {
        $productList = $this->getList();
        uksort($productList, function ($product1Key, $product2Key) {
            $product1Price = $this->productCatalog->getProductByName($product1Key)->getPrice();
            $product2Price = $this->productCatalog->getProductByName($product2Key)->getPrice();
            return $product1Price <=> $product2Price;
        });
        $this->list = $productList;
    }

    public function getProductQuantityByName(string $productName): int
    {
        return $this->isProductExist($productName) ? $this->getList()[$productName] : 0;
    }

    public function getProductTotalQuantity($exceptionProductNames = []): int
    {
        $productList = $this->getDiffListByKeys($exceptionProductNames);
        return array_reduce($productList, function ($acc, $item) {
            return $acc += $item;
        }, 0);
    }
}
