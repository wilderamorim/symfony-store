<?php

namespace App\Controller\Web;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produtos", name="products_")
 */
class ProductController extends AbstractController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);

        $products = $this->productRepository->findBy([], ['id' => 'DESC']);
        $products = $paginator->paginate($products, $page, 12);

        return $this->render('web/product/index.html.twig', compact('products'));
    }

    /**
     * @Route("/{slug}", name="show", methods={"GET"})
     */
    public function show(Product $product)
    {
        return $this->render('web/product/show.html.twig', compact('product'));
    }
}
