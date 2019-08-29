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
}
