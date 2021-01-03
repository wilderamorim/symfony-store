<?php

namespace App\Controller\Admin;

use App\Entity\Product;
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
     * @Route("/create", name="create", methods={"GET"})
     */
    public function create(): Response
    {
        return $this->render('admin/product/create.html.twig');
    }

    /**
     * @Route("/", name="store", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        try {
            $data = $request->request->all();
            $now = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));

            $product = new Product();
            $product->setName($data['name']); // $request->request->get('name')
            $product->setDescription($data['description']);
            $product->setBody($data['body']);
            $product->setSlug($data['slug']);
            $product->setPrice($data['price']);
            $product->setCreatedAt($now);
            $product->setUpdatedAt($now);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Produto cadastrado com sucesso!');
            return $this->redirectToRoute('admin_products_index');
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }

    /**
     * @Route("/{product}/edit", name="edit", methods={"GET"})
     */
    public function edit(Request $request): Response
    {
        $id = $request->attributes->get('product');

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if (!$product) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/{product}", name="update", methods={"PUT"})
     */
    public function update(Request $request): Response
    {
        $id = $request->attributes->get('product');

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if (!$product) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_products_index');
        }

        try {
            $data = $request->request->all();

            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setBody($data['body']);
            $product->setSlug($data['slug']);
            $product->setPrice($data['price']);
            $product->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash('success', 'Produto atualizado com sucesso!');
            return $this->redirectToRoute('admin_products_edit', ['product' => $product->getId()]);
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
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
