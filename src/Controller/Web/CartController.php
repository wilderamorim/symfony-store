<?php

namespace App\Controller\Web;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/carrinho", name="cart_")
 */
class CartController extends AbstractController
{
    private $cart;

    /**
     * CartController constructor.
     */
    public function __construct(CartService $cartService)
    {
        $this->cart = $cartService;
    }


    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $cart = $this->cart->getAll();

        return $this->render('web/cart/index.html.twig', compact('cart'));
    }

    /**
     * @Route("/add/{item}", name="add")
     */
    public function add($item, Request $request, ProductRepository $productRepository): Response
    {
        $amount = $request->request->get('amount');
        $product = $productRepository->findOneBySlugToCart($item);
        if ($amount <= 0 || !$product) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('cart_index');
        }

        $product['price'] = $product['price'] / 100;
        $product['amount'] = $amount;

        $result = $this->cart->addItem($product);

        $this->addFlash('success', $result);
        return $this->redirectToRoute('products_show', ['slug' => $product['slug']]);
    }

    /**
     * @Route("/remove/{item}", name="remove")
     */
    public function remove($item): Response
    {
        $this->cart->removeItem($item);

        $this->addFlash('success', 'Produto removido com sucesso!');
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/destroy", name="destroy")
     */
    public function destroy(): Response
    {
        $this->cart->destroyCart();

        $this->addFlash('success', 'Carrinho esvaziado com sucesso!');
        return $this->redirectToRoute('cart_index');
    }
}
