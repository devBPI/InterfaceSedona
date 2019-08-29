<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserSelectionDocument
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class UserSelectionDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserSelectionCategory", inversedBy="documents", cascade={"all"})
     */
    private $Category;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @var string
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @var string
     */
    private $document_type;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    private $creation_date;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @var string
     */
    private $position;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $comment;
}
