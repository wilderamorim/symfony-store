<?php

namespace App\Controller\Admin;

use App\Entity\ProductPhoto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductPhotoController extends AbstractController
{
    /**
     * @Route("/admin/product/photo/{photo}", name="admin_product_photo_destroy")
     */
    public function destroy(ProductPhoto $photo, EntityManagerInterface $entityManager): Response
    {
        $productId = $photo->getProduct()->getId();

        $entityManager->remove($photo);
        $entityManager->flush();

        $path = $this->getParameter('upload_dir') . '/products/' . $photo->getImage();
        if (is_file($path)) {
            unlink($path);
        }

        return $this->redirectToRoute('admin_products_edit', ['product' => $productId]);
    }
}
