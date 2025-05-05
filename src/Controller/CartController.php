<?php
// src/Controller/CartController.php
namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/add/{id}', name: 'cart_add')]
    public function add(Product $product, CartService $cartService): Response
    {
        $cartService->add($product);
        return $this->redirectToRoute('cart_show');
    }

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function remove(int $id, CartService $cartService): Response
    {
        $cartService->remove($id);
        return $this->redirectToRoute('cart_show');
    }

    #[Route('/', name: 'cart_show')]
    public function show(CartService $cartService, ProductRepository $productRepository): Response
    {
        $cartData = $cartService->getCart();
        $items = [];
        $total = 0;

        foreach ($cartData as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                $items[] = ['product' => $product, 'quantity' => $quantity];
                $total += $product->getPrice() * $quantity;
            }
        }

        return $this->render('cart/show.html.twig', [
            'items' => $items,
            'total' => $total
        ]);
    }
}
