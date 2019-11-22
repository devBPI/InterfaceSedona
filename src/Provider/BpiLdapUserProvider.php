<?php

namespace App\Provider;


use App\Model\LdapUser;
use InvalidArgumentException;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class BpiLdapUserProvider
 * @package App\Provider
 */
class BpiLdapUserProvider implements UserProviderInterface
{
    /** @var Ldap */
    private $ldap;
    private $baseDn;
    private $searchDn;
    private $searchPassword;
    private $defaultRoles;
    private $uidKey;
    private $defaultSearch;
    private $passwordAttribute;

    /**
     * @var array
     */
    private $fields;

    /**
     * BpiLdapUserProvider constructor.
     * @param LdapInterface $ldap
     * @param string $baseDn
     * @param string|null $searchDn
     * @param string|null $searchPassword
     * @param array $defaultRoles
     * @param array $fields
     * @param string|null $uidKey
     * @param string|null $filter
     * @param string|null $passwordAttribute
     */
    public function __construct(
        LdapInterface $ldap,
        string $baseDn,
        string $searchDn = null,
        string $searchPassword = null,
        array $defaultRoles = [],
        array $fields = [],
        string $uidKey = null,
        string $filter = null,
        string $passwordAttribute = null
    ) {
        if (null === $uidKey) {
            $uidKey = 'sAMAccountName';
        }

        if (null === $filter) {
            $filter = '({uid_key}={username})';
        }

        $this->ldap = $ldap;
        $this->baseDn = $baseDn;
        $this->searchDn = $searchDn;
        $this->searchPassword = $searchPassword;
        $this->defaultRoles = $defaultRoles;
        $this->uidKey = $uidKey;
        $this->defaultSearch = str_replace('{uid_key}', $uidKey, $filter);
        $this->passwordAttribute = $passwordAttribute;

        $this->fields = $fields;
    }

    /**
     * @param string $username
     * @return LdapUser|\Symfony\Component\Security\Core\User\User|UserInterface
     */
    public function loadUserByUsername($username)
    {
        $this->ldap->bind($this->searchDn, $this->searchPassword);
        $username = $this->ldap->escape($username, '', LdapInterface::ESCAPE_FILTER);
        $query = str_replace('{username}', $username, $this->defaultSearch);

        $options = [];
        if (!empty($this->fields)) {
            $options['filter'] = $this->fields;
        }
        $search = $this->ldap->query($this->baseDn, $query, $options);

        $entries = $search->execute();
        $count = \count($entries);

        if (!$count) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        if ($count > 1) {
            throw new UsernameNotFoundException('More than one user found');
        }

        $entry = $entries[0];

        try {
            if (null !== $this->uidKey) {
                $username = $this->getAttributeValue($entry, $this->uidKey);
            }
        } catch (InvalidArgumentException $e) {
        }

        return $this->loadUser($username, $entry);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return LdapUser::class === $class;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    /**
     * Loads a user from an LDAP entry.
     *
     * @param string $username
     * @param Entry $entry
     *
     * @return LdapUser
     */
    protected function loadUser($username, Entry $entry): LdapUser
    {
        return new LdapUser($entry->getAttributes(), $this->defaultRoles);
    }

    /**
     * Fetches a required unique attribute value from an LDAP entry.
     *
     * @param Entry|null $entry
     * @param string $attribute
     * @return
     */
    private function getAttributeValue(Entry $entry, $attribute)
    {
        if (!$entry->hasAttribute($attribute)) {
            throw new InvalidArgumentException(sprintf('Missing attribute "%s" for user "%s".', $attribute, $entry->getDn()));
        }

        $values = $entry->getAttribute($attribute);

        if (1 !== \count($values)) {
            throw new InvalidArgumentException(sprintf('Attribute "%s" has multiple values.', $attribute));
        }

        return $values[0];
    }
}
