<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class LdapUser
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class LdapUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserHistory", mappedBy="User")
     */
    private $histories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSelectionCategory", mappedBy="User")
     */
    private $selections;

    /**
     * LdapUser constructor.
     * @param string $ldap_uuid
     */
    public function __construct(string $ldap_uuid)
    {
        $this->id = $ldap_uuid;
    }

    /**
     * @return mixed
     */
    public function getHistories()
    {
        return $this->histories;
    }

    /**
     * @return mixed
     */
    public function getSelections()
    {
        return $this->selections;
    }

    public function __toString()
    {
        return $this->id;
    }
}
