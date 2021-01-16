<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;

    /**
     * CartService constructor.
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getAll()
    {
        return $this->session->has('cart') ? $this->session->get('cart') : [];
    }

    public function addItem($item)
    {
        $cart = $this->getAll();
        $message = 'Produto adicionado com sucesso!';

        if (count($cart)) {
            $slug = array_column($cart, 'slug');

            if (in_array($item['slug'], $slug)) {
                $cart = $this->incrementCartSlug($cart, $item);
                $message = sprintf('Quantidade do Produto %s aumentada com sucesso!', $item['name']);
            } else {
                array_push($cart, $item);
            }

        } else {
            $cart[] = $item;
        }


        $this->session->set('cart', $cart);

        return $message;
    }

    public function removeItem($item)
    {
        $cart = $this->getAll();

        $cart = array_filter($cart, function ($items) use ($item) {
            return $items['slug'] != $item;
        });

        if (!$cart) {
            return $this->session->remove('cart');
        }

        $this->session->set('cart', $cart);
    }

    public function destroyCart(): bool
    {
        $this->session->remove('cart');
        return true;
    }

    private function incrementCartSlug($cart, $item)
    {
        $arrayMap = array_map(function ($line) use ($item) {
            if ($line['slug'] == $item['slug']) {
                $line['amount'] += $item['amount'];
            }
            return $line;
        }, $cart);

        return $arrayMap;
    }
}