<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/account', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/my-account', name: 'app_user')]
    public function me(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
        ]);

        return $this->json(
            data:$user,
            context:['groups' => ['user:read']]
        );
    }

    #[Route('/account/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/account/activate-api', name: 'app_user_activate_api', methods: ['POST'])]
    public function activateAccessToApi(EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        /**
         * @var User $user
         */
        $user->enableApiAccess();
        $manager->flush();

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/account/deactivate-api', name: 'app_user_deactivate_api', methods: ['POST'])]
    public function deactivateAccessToApi(EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        /**
         * @var User $user
         */
        $user->disableApiAccess();
        $manager->flush();

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }
}
