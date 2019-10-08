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
     * @ORM\ManyToOne(targetEntity="UserSelectionList", inversedBy="documents")
     */
    private $List;

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
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $permalink;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $url;

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
        $this->permalink = $data['id'];
        $this->url = $data['url'];
        $this->creation_date = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        if (empty($this->id)) {
            return $this->permalink;
        }

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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
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
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param mixed $List
     * @return self
     */
    public function setList($List): self
    {
        $this->List = $List;

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
