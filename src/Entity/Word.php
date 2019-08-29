<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Word
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Word
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
}
