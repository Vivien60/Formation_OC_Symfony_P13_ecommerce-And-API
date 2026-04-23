<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiUserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user): void
    {
        /**
         * @var User $user
         */
        if($user->isApiAccessEnabled() === false) {
            throw new CustomUserMessageAccountStatusException(message: 'Accès API non activé', code: 403);
        }
        return;
    }

    public function checkPostAuth(UserInterface $user): void
    {
        return;
    }
}