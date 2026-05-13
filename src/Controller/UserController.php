<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

final class UserController extends AbstractController
{
    //Not implemented
    #[Route('/account', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * Display the account detail of the current user
     */
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
    #[IsCsrfTokenValid('delete-account', tokenKey: '_token')]
    public function delete(EntityManagerInterface $manager, Request $request): Response
    {
        $user = $this->getUser();
        $manager->remove($user);
        $manager->flush();
        $this->container->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/account/activate-api', name: 'app_user_activate_api', methods: ['POST'])]
    #[IsCsrfTokenValid('activate-api', tokenKey: '_token')]
    public function activateAccessToApi(EntityManagerInterface $manager, Security $security)
    {
        $user = $this->getUser();
        /**
         * @var User $user
         */
        $user->enableApiAccess();
        $manager->flush();

        $security->login($user);

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/account/deactivate-api', name: 'app_user_deactivate_api', methods: ['POST'])]
    #[IsCsrfTokenValid('deactivate-api', tokenKey: '_token')]
    public function deactivateAccessToApi(EntityManagerInterface $manager, Security $security)
    {
        $user = $this->getUser();
        /**
         * @var User $user
         */
        $user->disableApiAccess();
        $manager->flush();

        $security->login($user);

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }
}
