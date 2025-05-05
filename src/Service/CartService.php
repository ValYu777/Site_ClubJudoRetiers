<?php

// src/Service/CartService.php
namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->session->get('cart', []);
        $productId = $product->getId();

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        $this->session->set('cart', $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$productId]);
        $this->session->set('cart', $cart);
    }

    public function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    public function clear(): void
    {
        $this->session->remove('cart');
    }
}
