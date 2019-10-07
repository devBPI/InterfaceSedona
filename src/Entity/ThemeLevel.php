<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ThemeLevel
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class ThemeLevel
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
     * @ORM\Column(type="json_array", nullable=false, options={"jsonb": true})
     * @var string
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Theme", inversedBy="levels", cascade={"all"})
     */
    private $Parent;

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
    public function getUrl($locale='fr'): string
    {
        return $this->url[$locale];
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->Parent;
    }
}
