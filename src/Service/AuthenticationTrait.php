<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\LdapUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Trait AuthenticationTrait
 * @package App\Service
 */
trait AuthenticationTrait
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @return bool
     */
    private function hasConnectedUser(): bool
    {
        return $this->tokenStorage->getToken() instanceof TokenInterface &&
            $this->tokenStorage->getToken()->getUser() instanceof LdapUser;
    }

    /**
     * @return LdapUser|object
     */
    private function getUser(): LdapUser
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
