<?php

namespace App\Controller\Web;

use App\Entity\Category;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorias", name="categories_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/{slug}", name="index")
     */
    public function index(Category $category, PaginatorInterface $paginator, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $products = $paginator->paginate($category->getProducts(), $page, 12);

        $category = $category->getName();

        return $this->render('web/category/index.html.twig', compact('category', 'products'));
    }
}
