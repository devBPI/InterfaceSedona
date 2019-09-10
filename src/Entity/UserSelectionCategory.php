<?php

namespace App\Entity;

use App\Model\LdapUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserSelectionCategory
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class UserSelectionCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @var string
     */
    private $user_uid;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @var string
     */
    private $position = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSelectionDocument", mappedBy="Category")
     * @var ArrayCollection
     */
    private $documents;

    /**
     * UserSelectionCategory constructor.
     * @param LdapUser $user
     * @param string $title
     */
    public function __construct(LdapUser $user, string $title)
    {
        $this->user_uid = $user->getUid();
        $this->title = $title;
        $this->documents = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return ArrayCollection
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    /**
     * @param string $title
     * @return UserSelectionCategory
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
