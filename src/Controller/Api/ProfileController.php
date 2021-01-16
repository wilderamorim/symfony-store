<?php

namespace App\Controller\Api;

use App\Form\UserProfileType;
use App\Repository\UserRepository;
use App\Service\Api\FormErrorsValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/profiles", name="api_profiles")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/{user}", name="show", methods={"GET"})
     */
    public function show($user, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($user);

        return $this->json([
            'data' => [
                'profile' => $user,
            ],
        ], 200, [], ['groups' => ['profile']]);
    }

    /**
     * @Route("/{user}", name="update", methods={"PUT"})
     */
    public function update($user, Request $request, UserRepository $userRepository, FormErrorsValidation $formErrorsValidation): Response
    {
        $user = $userRepository->find($user);
        $form = $this->createForm(UserProfileType::class, $user);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->json([$form->getErrors()], 400);

//            return $this->json([
//                'data' => [
//                    'errors' => $formErrorsValidation->getErrors($form)
//                ],
//            ], 400);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'data' => [
                'message' => 'Perfil atualizado com sucesso!',
            ],
        ]);
    }

    /**
     * @Route("/password", name="password", methods={"PUT", "PATCH"})
     */
    public function password(
        Request $request,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        $password = $request->request->get('password');
        if (!$password) {
            return $this->json([
                'data' => [
                    'message' => 'O campo senha é requirido',
                ],
            ], 400);
        }

        $user = $userRepository->find(1);

        $password = $passwordEncoder->encodePassword($user, $password);
        $user->setPassword($password);

        $this->getDoctrine()->getManager()->flush();

        return $this->json([
            'message' => 'Senha atualizada com sucesso!',
        ]);
    }
}
