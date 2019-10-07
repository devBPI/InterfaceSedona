<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Theme
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Theme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="json_array", nullable=false, options={"jsonb": true})
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @var string
     */
    private $image;

    /**
     * @var Thematic
     * @ORM\ManyToOne(targetEntity="App\Entity\Thematic", inversedBy="themes", cascade={"all"})
     */
    private $Parent;

    /**
     * @ORM\OneToMany(targetEntity="ThemeLevel", mappedBy="Parent")
     */
    private $levels;

    /**
     * @ORM\Column(type="json_array", nullable=false, options={"jsonb": true})
     * @var string
     */
    private $url;

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
    public function getTitle($locale='fr'): string
    {
        return $this->title[$locale];
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->Parent;
    }

    /**
     * @return mixed
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * @return string
     */
    public function getUrl($locale='fr'): string
    {
        return $this->url[$locale];
    }

}
