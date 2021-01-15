<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/users", name="admin_users_")
 */
class UserController extends AbstractController
{
    private $em;                // $this->getDoctrine()->getManager()
    private $userRepository;    // $this->getDoctrine()->getRepository(User::class)

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->em = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $this->userRepository->findAll()
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword(new User(), $form['password']->getData());
            $user = $form->getData();
            $user->setPassword($password);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Usuário cadastrado com sucesso!');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{user}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $id = $request->attributes->get('user');

        $user = $this->userRepository->find($id);
        if (!$user) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_users_index');
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($password = $form['password']->getData()) {
                $password = $passwordEncoder->encodePassword($user, $form['password']->getData());
                $user->setPassword($password);
            }

            $this->em->flush();

            $this->addFlash('success', 'Usuário atualizado com sucesso!');
            return $this->redirectToRoute('admin_users_edit', ['user' => $user->getId()]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{user}", name="destroy", methods={"DELETE"})
     */
    public function destroy(Request $request): Response
    {
        $id = $request->attributes->get('user');

        $user = $this->userRepository->find($id);
        if (!$user) {
            $this->addFlash('danger', 'Algo deu errado, tente novamente em alguns minutos');
            return $this->redirectToRoute('admin_users_index');
        }

        try {
            $this->em->remove($user);
            $this->em->flush();

            $this->addFlash('success', 'Usuário excluído com sucesso!');
            return $this->redirectToRoute('admin_users_index');
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }
}
