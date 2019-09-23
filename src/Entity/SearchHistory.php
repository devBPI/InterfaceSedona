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
     * @ORM\Column(type="json_array", nullable=false, options={"jsonb": true})
     * @var array
     */
    private $queries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserHistory", mappedBy="Search")
     */
    private $userHistories;

    /**
     * SearchHistory constructor.
     * @param string $title
     * @param array $queries
     */
    public function __construct(string $title, array $queries = [])
    {
        $this->id = self::getSearchHash($queries);
        $this->title = $title;
        $this->queries = $queries;
    }

    /**
     * @param array $queries
     * @return string
     */
    public static function getSearchHash(array $queries): string
    {
        return sha1(serialize($queries));
    }

    /**
     * @return int
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
     * @return array
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

}
