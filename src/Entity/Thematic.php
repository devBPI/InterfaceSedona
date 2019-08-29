<?php

namespace App\Entity;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Theme", mappedBy="Parent")
     */
    private $themes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Word")
     */
    private $words;
}
