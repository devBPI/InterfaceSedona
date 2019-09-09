<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserSelectionCategory
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class UserSelectionCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @var string
     */
    private $user_uid;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @var string
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSelectionDocument", mappedBy="Category")
     */
    private $documents;
}
