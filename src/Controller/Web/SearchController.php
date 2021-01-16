<?php

namespace App\Controller\Web;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/busca", name="search_")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, ProductRepository $productRepository, PaginatorInterface $paginator): Response
    {
        $search = $request->query->get('s');
        $page = $request->query->get('page', 1);

        $products = $productRepository->findBySearch($search);
        $products = $paginator->paginate($products, $page, 12);

        return $this->render('web/search/index.html.twig', compact('search', 'products'));
    }
}
