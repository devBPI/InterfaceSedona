<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserHistory
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class UserHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LdapUser", inversedBy="histories", cascade={"remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $User;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="json_array", nullable=false, options={"jsonb": true})
     * @var array
     */
    private $queries;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @var string
     */
    private $url;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    private $creation_date;

    /**
     * UserHistory constructor.
     * @param LdapUser $user
     * @param string $title
     * @param array $queries
     */
    public function __construct(?LdapUser $user, string $title, array $queries = [])
    {
        $this->User = $user;
        $this->title = $title;
        $this->queries = $queries;
        $this->url = md5(serialize($queries));
        $this->creation_date = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return LdapUser|null
     */
    public function getUser(): ?LdapUser
    {
        return $this->User;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creation_date;
    }

}
