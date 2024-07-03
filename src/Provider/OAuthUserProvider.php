<?php

namespace App\Provider;

use App\Model\LdapUser;
use KnpU\OAuth2ClientBundle\Security\User\OAuthUser;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class OAuthUserProvider implements UserProviderInterface
{
    /** @var Ldap */
    private $ldap;
    private $baseDn;
    private $searchDn;
    private $searchPassword;
    private $defaultRoles;
    private $uidKey;
    private $defaultSearch;

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
     */
    public function __construct(
        LdapInterface $ldap,
        string $baseDn,
        string $searchDn = null,
        string $searchPassword = null,
        array $defaultRoles = [],
        array $fields = [],
        string $uidKey = null,
        string $filter = null
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

        $this->fields = $fields;
    }

    public function loadUserByUsername($username): LdapUser
    {
        // Créez et renvoyez un utilisateur en fonction du nom d'utilisateur (dans ce cas, un utilisateur OAuth)
        //return new OAuthUser($username);
	        // Charger l'utilisateur à partir de la base de données ou d'un autre système
        // Ici, nous retournons un utilisateur basique en tant qu'exemple
        //return new User($username, null, ['ROLE_USER']);

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

        if (null !== $this->uidKey) {
            $username = $this->getAttributeValue($entry, $this->uidKey);
        }

        return $this->loadUser($username, $entry);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof OAuthUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return OAuthUser::class === $class;
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
     * @return mixed
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

