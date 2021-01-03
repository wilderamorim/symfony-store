<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products", name="admin_products_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('admin/product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $product = $form->getData();
            $product->setCreatedAt();

            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Produto cadastrado com sucesso!');
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{product}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request): Response
    {
        $id = $request->attributes->get('product');

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if (!$product) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_products_index');
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $product = $form->getData();
            $product->setUpdatedAt();

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Produto atualizado com sucesso!');
            return $this->redirectToRoute('admin_products_edit', ['product' => $product->getId()]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{product}", name="destroy", methods={"DELETE"})
     */
    public function destroy(Request $request): Response
    {
        $id = $request->attributes->get('product');

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if (!$product) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_products_index');
        }

        try {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($product);
            $manager->flush();

            $this->addFlash('success', 'Produto excluído com sucesso!');
            return $this->redirectToRoute('admin_products_index');
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }
}
