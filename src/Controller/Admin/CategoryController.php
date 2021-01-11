<?php

namespace App\Controller\Admin;

use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categories", name="admin_categories_")
 */
class CategoryController extends AbstractController
{
    private $em;                // $this->getDoctrine()->getManager()
    private $categoryRepository; // $this->getDoctrine()->getRepository(Category::class)

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->em = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $this->categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $this->em->persist($category);
            $this->em->flush();

            $this->addFlash('success', 'Categoria cadastrada com sucesso!');
            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{category}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request): Response
    {
        $id = $request->attributes->get('category');

        $category = $this->categoryRepository->find($id);
        if (!$category) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_categories_index');
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Categoria atualizada com sucesso!');
            return $this->redirectToRoute('admin_categories_edit', ['category' => $category->getId()]);
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{category}", name="destroy", methods={"DELETE"})
     */
    public function destroy(Request $request): Response
    {
        $id = $request->attributes->get('category');

        $category = $this->categoryRepository->find($id);
        if (!$category) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_categories_index');
        }

        try {
            $this->em->remove($category);
            $this->em->flush();

            $this->addFlash('success', 'Categoria excluída com sucesso!');
            return $this->redirectToRoute('admin_categories_index');
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }
}
