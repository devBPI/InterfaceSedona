<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ThemeLevel
 * @package App\Entity
 *
 * @ORM\Entity()
 */
class ThemeLevel
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
     * @ORM\Column(type="json_array", nullable=false, options={"jsonb": true})
     * @var array
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Theme", inversedBy="levels", cascade={"all"})
     * @var Theme
     */
    private $Parent;

    /**
     * @var string
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private ?string $code = "";

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

    /**
     * @return Theme
     */
    public function getParent(): Theme
    {
        return $this->Parent;
    }
}
