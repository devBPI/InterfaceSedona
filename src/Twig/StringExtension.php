<?php
declare(strict_types=1);

namespace App\Twig;


use App\Service\TraitSlugify;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;


class StringExtension extends AbstractExtension
{
    use TraitSlugify;

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('snake', [$this, 'toSnakeCase']),
            new TwigFilter('camel', [$this, 'toCamelCase']),
            new TwigFilter('slugify', [$this, 'toSlugify']),
            new TwigFilter('wordwrap', [$this, 'wordwrap']),
            new TwigFilter('truncate', [$this, 'truncate']),
            new TwigFilter('carrouselTitle', [$this, 'carrouselTitle']),
        ];
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('class_name', [$this, 'getClass']),
            new TwigFunction('is_same_instance', [$this, 'sameInstance']),

        ];
   }


    /**
     * @param mixed $object
     * @return string
     * @throws \ReflectionException
     */
    public function getClass($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'string_extension';
    }

    /**
     * @param mixed $object
     * @param string $class
     * @return bool
     */
    public function sameInstance($object, string$class)
    {
        return $object instanceof $class;
    }

    /**
     * @param string $text
     * @return string
     */
    public function toSnakeCase(string $text): string
    {
        return strtolower(ltrim(preg_replace('/([A-Z])/', '_\\1', $text), '_'));
    }

    /**
     * @param string $text
     * @return array
     */
    public function wordwrap(string $text,
                             $width = 25,
                             string $break = "\n",
                             bool $cut_long_words = true
    ): array
    {
        return explode($break, wordwrap($text,$width,$break,$cut_long_words));
    }

    /**
     * @param string $text
     * @return string
     */
    public function truncate(string $text, int $length = 20, string $ellipsis = '[…]'): string
    {
        return mb_strlen($text)>$length ? substr($text,0,$length).$ellipsis : $text;
    }

    public function carrouselTitle(string $text,$width = 24, string $ellipsis = '[…]') :string
    {
        $playlod = $this->wordwrap($text,$width);
        if (count($playlod)>2) {
            $nb = $width-strlen($ellipsis);
            $subPayload = $this->wordwrap($playlod[1],$nb,"\n", false);
            $subPayload[0] = strlen($subPayload[0]) < $nb ? trim($subPayload[0])." " : trim($subPayload[0]);
            return $playlod[0]."\n".$subPayload[0].$ellipsis;
        } elseif (count($playlod) == 2) {
            return implode("\n",$playlod);
        } else {
            return $playlod[0];
        }
    }

    /**
     * @param string $text
     * @return string
     */
    public function toCamelCase(string $text): string
    {
        return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[^a-zA-Z0-9\x7f-\xff]++/', ' ', $text))));
    }

    /**
     * @param string $text
     * @return string
     */
    public function toSlugify(string $text) :string
    {
        return $this->slugify($text);
    }

}
