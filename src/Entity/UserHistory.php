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
     * @ORM\Column(type="string", length=50, nullable=true)
     * @var string
     */
    private $user_uid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SearchHistory", inversedBy="userHistories")
     */
    private $Search;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    private $creation_date;

    /**
     * UserHistory constructor.
     *
     * @param SearchHistory $objSearch
     */
    public function __construct(SearchHistory $objSearch)
    {
        $this->Search = $objSearch;
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
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creation_date;
    }

    /**
     * @param string $user_uid
     * @return self
     */
    public function setUserUid($user_uid): self
    {
        $this->user_uid = $user_uid;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        if ($this->Search instanceof SearchHistory) {
            return $this->Search->getTitle();
        }

        return 'Recherche anonyme';
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        if ($this->Search instanceof SearchHistory) {
            return $this->Search->getId();
        }

        return null;
    }
}
