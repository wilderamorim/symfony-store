<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        $name = 'Wilder';

        $now = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));

        #create
//        $product = new Product();
//        $product->setName('Produto Teste');
//        $product->setDescription('Descrição');
//        $product->setBody('Conteúdo');
//        $product->setSlug('produto-teste');
//        $product->setPrice(1990);
//        $product->setCreatedAt($now);
//        $product->setUpdatedAt($now);
//
//        $manager = $this->getDoctrine()->getManager();
//        $manager->persist($product);
//        $manager->flush();
        #end create

        #update
//        $product = $this->getDoctrine()->getRepository(Product::class)->find(1);
//        $product->setName('Produto Teste EDITADO');
//        $product->setUpdatedAt($now);
//
//        $manager = $this->getDoctrine()->getManager();
//        $manager->flush();
        #end update

        #delete
//        $product = $this->getDoctrine()->getRepository(Product::class)->find(1);
//
//        $manager = $this->getDoctrine()->getManager();
//        $manager->remove($product);
//        $manager->flush();
        #end delete

        /* ---------- */

        #find all
//        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
//        dump($products);

        #find by
//        $products = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['id' => 2]);
//        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(['id' => 2]);
//        $products = $this->getDoctrine()->getRepository(Product::class)->findOneByPrice(1990);
//        $products = $this->getDoctrine()->getRepository(Product::class)->findOnePrice(1990);
//        dd($products);

        /* ---------- */

//        $manager = $this->getDoctrine()->getManager();
//
//        $user = new User();
//        $user->setFirstName('Wilder');
//        $user->setLastName('Amorim');
//        $user->setEmail('wilderamorim@msn.com');
//        $user->setPassword(md5(12345678));
//        $user->setCreatedAt($now);
//        $user->setUpdatedAt($now);
//
//        $manager->persist($user);
//        $manager->flush();
//
//        $address = new Address();
//        $address->setUser($user);
//        $address->setStreet('Rua João Lustosa');
//        $address->setNumber(55);
//        $address->setNeighborhood('Lourdes');
//        $address->setCity('Juiz de Fora');
//        $address->setState('MG');
//        $address->setZipCode('36070-720');
//
//        $manager->persist($address);
//        $manager->flush();

        /* ---------- */

        $address = $this->getDoctrine()->getRepository(Address::class)->find(1);
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);

        return $this->render('index.html.twig', compact('name', 'address', 'user'));
    }

    /**
     * @Route("/product/{slug}", name="product_single")
     */
    public function product($slug): Response
    {
        return $this->render('single.html.twig', compact('slug'));
    }
}
