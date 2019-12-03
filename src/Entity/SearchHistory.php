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
     * SearchHistory constructor.
     * @param string $title
     * @param string $queryString
     */
    public function __construct(string $title, string $queryString)
    {
        $this->id = self::getSearchHash($queryString);
        $this->title = $title;
        $this->queryString = $queryString;
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

}
