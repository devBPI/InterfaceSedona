<?php

namespace App\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package App\Model
 */
class LdapUser implements UserInterface
{
    const UID_KEY = 'entryUUID';
    const USERNAME_KEY = 'pseudo';
    const LASTNAME_KEY = 'sn';
    const FIRSTNAME_KEY = 'cn';
    const EMAIL_KEY = 'mail';

    private static $mandatory_fields = [
        self::USERNAME_KEY => 'username',
        self::UID_KEY => 'uid'
    ];

    /** @var null|string  */
    protected $uid;
    protected $username;
    protected $lastname;
    protected $firstname;
    protected $email;

    private $password;
    private $enabled;
    private $roles;

    /**
     * LdapUser constructor.
     *
     * @param array $data
     * @param array $roles
     * @param bool $enabled
     */
    public function __construct(array $data = [], array $roles = [], bool $enabled = true)
    {
        foreach (self::$mandatory_fields as $field => $label) {
            if (
                !(array_key_exists($field, $data) && isset($data[$field][0])) ||
                (empty($data[$field][0]))
            ) {
                throw new \InvalidArgumentException('The '.$label.' cannot be empty.');
            }
        }

        $this->enabled = $enabled;
        $this->roles = $roles;

        $this->setEntryAttributes($data);
    }

    /**
     * @param array $data
     */
    public function setEntryAttributes(array $data = []): void
    {
        if (array_key_exists(self::UID_KEY, $data) && isset($data[self::UID_KEY][0])) {
            $this->uid = $data[self::UID_KEY][0];
        }
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
    public function getUid(): string
    {
        return $this->uid;
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
