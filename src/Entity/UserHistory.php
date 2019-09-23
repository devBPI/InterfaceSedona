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
     * @ORM\ManyToOne(targetEntity="App\Entity\SearchHistory")
     * @var SearchHistory
     */
    private $Search;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    private $creation_date;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    private $last_view_date;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $count = 0;

    /**
     * UserHistory constructor.
     *
     * @param SearchHistory $objSearch
     */
    public function __construct(SearchHistory $objSearch)
    {
        $this->Search = $objSearch;
        $this->creation_date = new \DateTime();
        $this->incrementCount();
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
     * @return \DateTime
     */
    public function getLastViewDate(): \DateTime
    {
        return $this->last_view_date;
    }

    /**
     * @param \DateTime $last_view_date
     * @return self
     */
    public function setLastViewDate(\DateTime $last_view_date): self
    {
        $this->last_view_date = $last_view_date;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return self
     */
    public function incrementCount(): self
    {
        $this->count++;
        $this->last_view_date = new \DateTime();

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
