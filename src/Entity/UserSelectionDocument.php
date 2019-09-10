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
     * @ORM\ManyToOne(targetEntity="App\Entity\UserSelectionCategory", inversedBy="documents")
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
    private $position = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $comment;

    /**
     * UserSelectionDocument constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->title = $data['name'];
        $this->author = $data['author'];
        $this->document_type = $data['type'];
        $this->creation_date = new \DateTime();
    }

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->document_type;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creation_date;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param mixed $Category
     * @return self
     */
    public function setCategory($Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    /**
     * @param string $comment
     * @return self
     */
    public function setComment($comment): self
    {
        $this->comment = $comment;

        return $this;
    }

}
