<?php

namespace App\Controller\Admin;

use App\Entity\ProductPhoto;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{File\UploadedFile, Request, Response};
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/products", name="admin_products_")
 */
class ProductController extends AbstractController
{
    private $em;                // $this->getDoctrine()->getManager()
    private $productRepository; // $this->getDoctrine()->getRepository(Product::class)

    public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        $this->em = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $this->productRepository->findAll()
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, UploadService $uploadService): Response
    {
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setCreatedAt();

            //upload
            if ($files = $form['photos']->getData()) {
                $images = $uploadService->upload($files, '/products');
                $productPhoto = $this->makeProductPhotoEntities($images);
                $product->addManyProductPhoto($productPhoto);
            }

            $this->em->persist($product);
            $this->em->flush();

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
    public function edit(Request $request, UploadService $uploadService): Response
    {
        $id = $request->attributes->get('product');

        $product = $this->productRepository->find($id);
        if (!$product) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_products_index');
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setUpdatedAt();

            //upload
            if ($files = $form['photos']->getData()) {
                $images = $uploadService->upload($files, '/products');
                $productPhoto = $this->makeProductPhotoEntities($images);
                $product->addManyProductPhoto($productPhoto);
            }

            $this->em->flush();

            $this->addFlash('success', 'Produto atualizado com sucesso!');
            return $this->redirectToRoute('admin_products_edit', ['product' => $product->getId()]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView(),
            'photos' => $product->getProductPhotos()
        ]);
    }

    /**
     * @Route("/{product}", name="destroy", methods={"DELETE"})
     */
    public function destroy(Request $request): Response
    {
        $id = $request->attributes->get('product');

        $product = $this->productRepository->find($id);
        if (!$product) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_products_index');
        }

        try {
            $this->em->remove($product);
            $this->em->flush();

            $this->addFlash('success', 'Produto excluído com sucesso!');
            return $this->redirectToRoute('admin_products_index');
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }

    private function makeProductPhotoEntities($images): array
    {
        $entities = [];
        foreach ($images as $image) {
            $productPhoto = new ProductPhoto();
            $productPhoto->setImage($image);
            $productPhoto->setCreatedAt();

            $entities[] = $productPhoto;
        }

        return $entities;
    }
}
