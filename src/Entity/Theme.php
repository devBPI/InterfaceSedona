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
     * @ORM\ManyToOne(targetEntity="App\Entity\Thematic", inversedBy="themes", cascade={"all"})
     */
    private $Parent;

    /**
     * @ORM\OneToMany(targetEntity="ThemeLevel", mappedBy="Parent")
     */
    private $levels;
}
