<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Theme
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class Theme implements ParcoursLinkInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json_array", nullable=false, options={"jsonb": true})
     * @var array
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @var string
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Thematic", inversedBy="themes", cascade={"all"})
     * @var Thematic
     */
    private $Parent;

    /**
     * @ORM\OneToMany(targetEntity="ThemeLevel", mappedBy="Parent")
     * @var ThemeLevel[]|ArrayCollection
     */
    private $levels;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private string $code;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(string $locale='fr'): string
    {
        if (array_key_exists($locale, $this->title)) {
            return $this->title[$locale];
        }
        return '';
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getParent(): ?Thematic
    {
        return $this->Parent;
    }

    public function getLevels(): Collection
    {
        return $this->levels;
    }

    public function getParcours(): string
    {
        return $this->Parent->getType();
    }
}
