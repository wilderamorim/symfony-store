<?php

namespace App\Controller\Web;

use App\Form\UserRegisterFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/cadastrar", name="register_")
 */
class RegisterController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        GuardAuthenticatorHandler $authenticatorHandler,
        LoginFormAuthenticator $loginFormAuthenticator
    ): Response {
        $form = $this->createForm(UserRegisterFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $password = $passwordEncoder->encodePassword($user, $form['password']->getData());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Seja bem-vindo(a)!');
            return $authenticatorHandler->authenticateUserAndHandleSuccess($user, $request, $loginFormAuthenticator, 'main');
        }

        return $this->render('web/register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
