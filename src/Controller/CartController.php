<?php

namespace App\Controller;

use App\Helper\ResponseBag;
use App\Repository\ProductRepository;
use App\Service\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/cart', condition: "request.headers.get('Content-Type') matches '~Application\/json~i'")]
class CartController extends AbstractController
{
    public const SESSION_CART = 'cart';

    public function __construct(private readonly ProductRepository $productRepository) {}

    #[Route(path: '', name: 'cart_get', methods: ['GET'])]
    public function get(Request $request) : ResponseBag
    {
        $session = $request->getSession();
        $cart = $session->get(self::SESSION_CART, new Cart());
        return new ResponseBag($cart, 200, ['cart']);
    }

    #[Route(path: '/{productId}', name: 'cart_add_product', requirements: ['productId' => '\d+'], methods: ['PUT'])]
    public function add(Request $request, int $productId) : ResponseBag
    {
        if (($product = $this->productRepository->find($productId)) === null) {
            return new ResponseBag(statusCode: 404);
        }

        $session = $request->getSession();
        /** @var Cart $cart */
        $cart = $session->get(self::SESSION_CART, new Cart());
        $cart->add($product);
        $session->set(self::SESSION_CART, $cart);

        return new ResponseBag($cart, 200, ['cart']);
    }

    #[Route(path: '/{productId}', name: 'cart_remove_product', requirements: ['productId' => '\d+'], methods: ['DELETE'])]
    public function remove(Request $request, int $productId) : ResponseBag
    {
        if (($product = $this->productRepository->find($productId)) === null) {
            return new ResponseBag(statusCode: 404);
        }

        $session = $request->getSession();
        /** @var Cart $cart */
        $cart = $session->get(self::SESSION_CART, new Cart());
        $cart->remove($product);
        $session->set(self::SESSION_CART, $cart);

        return new ResponseBag($cart, 200, ['cart']);
    }
}
