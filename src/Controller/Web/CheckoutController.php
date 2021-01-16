<?php

namespace App\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checkout", name="checkout_")
 */
class CheckoutController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('cart')) {
            return $this->redirectToRoute('products_index');
        }

        $cart = $session->get('cart');

        return $this->render('web/checkout/index.html.twig', compact('cart'));
    }

    /**
     * @Route("/obrigado", name="thanks")
     * @IsGranted("ROLE_USER")
     */
    public function thanks(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('cart')) {
            $this->redirectToRoute('web_index');
        }

        $cart = $session->get('cart');
        $session->remove('cart');

        return $this->render('web/checkout/thanks.html.twig', compact('cart'));
    }
}
