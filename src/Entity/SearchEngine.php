<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SearchEngine
 * @package App\Entity
 * @deprecated  class non utilisé
 * @ORM\Entity()
 */
class SearchEngine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @var string
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @var string
     */
    private $url;
}

