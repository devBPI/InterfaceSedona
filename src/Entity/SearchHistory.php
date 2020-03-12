<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SearchHistory
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class SearchHistory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @var string
     */
    private $queryString;
    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     * @var string
     */
    private $parcours;

    /**
     * SearchHistory constructor.
     * @param string $title
     * @param string $queryString
     * @param string  $parcours
     */
    public function __construct(string $title, string $queryString, string $parcours=null)
    {
        $this->id = self::getSearchHash($queryString);
        $this->title = $title;
        $this->queryString = $queryString;
        $this->parcours= $parcours;
    }

    /**
     * @param string $queryString
     * @return string
     */
    public static function getSearchHash(string $queryString): string
    {
        return sha1($queryString);
    }

    /**
     * @return string
     */
    public function getId(): string
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
     * @return string
     */
    public function getQueryString(): string
    {
        return $this->queryString;
    }

    /**
     * @return null|string
     */
    public function getParcours(): ?string
    {
        return $this->parcours;
    }

}
