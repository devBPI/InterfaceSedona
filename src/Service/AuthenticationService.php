<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\LdapUser;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class AuthenticationService
 * @package App\Service
 */
abstract class AuthenticationService
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * AuthenticationService constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param SessionInterface $session
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    /**
     * @return bool
     */
    protected function hasConnectedUser(): bool
    {
        return $this->tokenStorage->getToken() instanceof TokenInterface &&
            $this->tokenStorage->getToken()->getUser() instanceof LdapUser;
    }

    /**
     * @return LdapUser|object
     */
    protected function getUser(): LdapUser
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @param string $key
     * @return array
     */
    protected function getSession(string $key): array
    {
        return $this->session->get($key, []);
    }

    /**
     * @param string $key
     * @param array $values
     */
    protected function setSession(string $key, array $values = []): void
    {
        $this->session->set($key, $values);
    }

    /**
     * @param string $key
     * @param array $values
     */
    protected function appendSession(string $key, array $values = []): void
    {
        $values = array_merge(
            $this->session->get($key, []),
            $values
        );

        $this->session->set($key, $values);
    }
}
