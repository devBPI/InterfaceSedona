<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @var string
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @var string
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserHistory", mappedBy="User")
     */
    private $histories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSelectionCategory", mappedBy="User")
     */
    private $selections;

}
