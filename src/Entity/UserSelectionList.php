<?php

namespace App\Entity;

use App\Model\LdapUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserSelectionList
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserSelectionListRepository")
 */
class UserSelectionList
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
     * @var int
     */
    private $position = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSelectionDocument", mappedBy="List", cascade={"all"})
     * @var ArrayCollection
     */
    private $documents;

    /**
     * UserSelectionList constructor.
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
     * @return UserSelectionList
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param UserSelectionDocument $document
     */
    public function addDocument(UserSelectionDocument $document): void
    {
        $document->setList($this);
        $this->documents->add($document);
    }

    /**
     * @param int $position
     * @return UserSelectionList
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}
