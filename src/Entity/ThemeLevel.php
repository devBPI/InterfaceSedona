<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ThemeLevel
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class ThemeLevel implements ParcoursLinkInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Theme", inversedBy="levels", cascade={"all"})
     * @var Theme
     */
    private $Parent;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private string $code = "";

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

    public function getParent(): Theme
    {
        return $this->Parent;
    }

    public function getParcours(): string
    {
        return $this->Parent->getParcours();
    }
}
