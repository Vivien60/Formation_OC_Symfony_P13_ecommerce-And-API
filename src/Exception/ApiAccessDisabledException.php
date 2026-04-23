<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class ApiAccessDisabledException extends CustomUserMessageAccountStatusException
{
    public function __construct(?Exception $previous = null)
    {
        parent::__construct(message: 'Accès API non activé', code: 403, previous: $previous);
    }
}