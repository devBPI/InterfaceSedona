<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Thematic
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Thematic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     */
    private $slug;
    /**
     * @var string
     * @ORM\Column(type="string", length=250, nullable=false)
     */
    private $type;

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
     * @ORM\Column(type="json_array", nullable=true, options={"jsonb": true})
     * @var string
     */
    private $description;

    /**
     * @var Theme[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Theme", mappedBy="Parent")
     */
    private $themes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Word")
     */
    private $words;

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $locale
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
     * @param string $locale
     * @return string
     */
    public function getDescription($locale='fr'): string
    {
        return $this->description[$locale];
    }

    /**
     * @return mixed
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * @return mixed
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
}

