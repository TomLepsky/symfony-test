<?php

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\Serializer\Annotation\Groups;

class Cart
{
    #[Groups(['cart'])]
    private array $products = [];

    #[Groups(['cart'])]
    private int $totalPrice = 0;

    #[Groups(['cart'])]
    private int $totalCount = 0;

    public function add(Product $newProduct) : void
    {
        if (($product = $this->findProduct($newProduct->getId())) === null) {
            $this->products[] = $newProduct;
        } else {
            $product->incrCount();
        }
        $this->totalPrice += $newProduct->getPrice();
        $this->totalCount++;
    }

    public function remove(Product $productToRemove) : void
    {
        if (($product = $this->findProduct($productToRemove->getId())) !== null) {
            if ($product->getCount() > 1) {
                $product->decrCount();
            } else {
                unset($this->products[$this->searchIndex($productToRemove->getId())]);
            }
            $this->totalPrice -= $productToRemove->getPrice();
            $this->totalCount--;
        }
    }

    private function findProduct(int $productId) : ?Product
    {
        if (($index = $this->searchIndex($productId)) !== null) {
            return $this->products[$index];
        }
        return null;
    }

    private function searchIndex(int $productId) : ?int
    {
        /** @var Product[] $products */
        for ($i = 0; $i < count($this->products); $i++) {
            if ($this->products[$i]->getId() === $productId) {
                return $i;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}