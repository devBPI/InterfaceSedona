<?php

namespace App\Model;

use App\Entity\LdapUser as EntityUser;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class LdapUser
 * @package App\Model
 */
class LdapUser implements UserInterface
{
    const USERNAME_KEY = 'pseudo';
    const LASTNAME_KEY = 'sn';
    const FIRSTNAME_KEY = 'cn';
    const EMAIL_KEY = 'mail';

    /** @var null|string  */
    private $username;
    private $lastname;
    private $firstname;
    private $email;

    /** @var null|LdapUser */
    private $entityUser;

    private $password;
    private $enabled;
    private $roles;

    /**
     * LdapUser constructor.
     * @param null|string $username
     * @param array $data
     * @param EntityUser $entityUser
     * @param array $roles
     * @param bool $enabled
     */
    public function __construct(string $username, array $data = [], EntityUser $entityUser, array $roles = [], bool $enabled = true)
    {
        if ('' === $username || null === $username) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }

        $this->entityUser = $entityUser;
        $this->enabled = $enabled;
        $this->roles = $roles;


        if (array_key_exists(self::USERNAME_KEY, $data) && isset($data[self::USERNAME_KEY][0])) {
            $this->username = $data[self::USERNAME_KEY][0];
        }
        if (array_key_exists(self::LASTNAME_KEY, $data) && isset($data[self::LASTNAME_KEY][0])) {
            $this->lastname = $data[self::LASTNAME_KEY][0];
        }
        if (array_key_exists(self::FIRSTNAME_KEY, $data) && isset($data[self::FIRSTNAME_KEY][0])) {
            $this->firstname = $data[self::FIRSTNAME_KEY][0];
        }
        if (array_key_exists(self::EMAIL_KEY, $data) && isset($data[self::EMAIL_KEY][0])) {
            $this->email = $data[self::EMAIL_KEY][0];
        }

    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getUsername();
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return EntityUser
     */
    public function getEntityUser(): ?EntityUser
    {
        return $this->entityUser;
    }


    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
