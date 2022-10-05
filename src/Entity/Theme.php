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
class Theme
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
     * @ORM\Column(type="json_array", nullable=false, options={"jsonb": true})
     * @var array
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private ?string $code;

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getTitle(string $locale='fr'): string
    {
        if (array_key_exists($locale, $this->title)) {
            return $this->title[$locale];
        }

        return '';
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return Thematic|null
     */
    public function getParent(): ?Thematic
    {
        return $this->Parent;
    }

    /**
     * @return Collection
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getUrl(string $locale='fr'): string
    {
        if (array_key_exists($locale, $this->url)) {
            return $this->url[$locale];
        }

        return '';
    }
}
