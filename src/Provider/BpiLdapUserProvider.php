<?php

namespace App\Provider;


use App\Entity\LdapUser as EntityUser;
use App\Model\LdapUser;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\LdapUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BpiLdapUserProvider
 * @package App\Provider
 */
class BpiLdapUserProvider extends LdapUserProvider
{
    const UID_KEY = 'entryUUID';

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
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var array
     */
    private $fields;

    /**
     * BpiLdapUserProvider constructor.
     * @param LdapInterface $ldap
     * @param EntityManager $entityManager
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
        EntityManager $entityManager,
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

        $this->entityManager = $entityManager;
        $this->fields = $fields;
    }

    /**
     * @param string $username
     * @return LdapUser|\Symfony\Component\Security\Core\User\User|UserInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function loadUserByUsername($username)
    {
        try {
            $this->ldap->bind($this->searchDn, $this->searchPassword);
            $username = $this->ldap->escape($username, '', LdapInterface::ESCAPE_FILTER);
            $query = str_replace('{username}', $username, $this->defaultSearch);

            $options = [];
            if (!empty($this->fields)) {
                $options['filter'] = $this->fields;
            }
            $search = $this->ldap->query($this->baseDn, $query, $options);
        } catch (ConnectionException $e) {
            throw new UsernameNotFoundException(sprintf('LdapUser "%s" not found.', $username), 0, $e);
        }

        $entries = $search->execute();
        $count = \count($entries);

        if (!$count) {
            throw new UsernameNotFoundException(sprintf('LdapUser "%s" not found.', $username));
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
//        return new LdapUser($user->getUsername(), $user->getRoles());
    }

    /**
     * Loads a user from an LDAP entry.
     *
     * @param string $username
     * @param Entry $entry
     *
     * @return LdapUser
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function loadUser($username, Entry $entry)
    {
        $ldap_uuid = $entry->getAttribute(self::UID_KEY)[0];
        $userEntity = $this->entityManager->getRepository(EntityUser::class)->find($ldap_uuid);
        if (!$userEntity instanceof EntityUser) {
            $userEntity = new EntityUser($ldap_uuid);
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush($userEntity);
        }

        return new LdapUser($username, $entry->getAttributes(), $userEntity, $this->defaultRoles);
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
