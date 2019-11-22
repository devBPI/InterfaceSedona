<?php
declare(strict_types=1);

namespace App\Twig;


use App\Service\TraitSlugify;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class StringExtension
 * @package App\Twig
 */
class StringExtension extends AbstractExtension
{
    use TraitSlugify;

    /**
     * @return array|TwigFunction[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('snake', [$this, 'toSnakeCase']),
            new TwigFilter('camel', [$this, 'toCamelCase']),
            new TwigFilter('slugify', [$this, 'toSlugify']),
        ];
    }


    public function getFunctions()
    {
        return [
        new TwigFunction('class_name', [$this, 'getClass']),
        new TwigFunction('is_same_instance', [$this, 'sameInstance']),

        ];
   }


    /**
     * @param $object
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
     * @param $object
     * @param $class
     * @return bool
     */
    public function sameInstance($object, $class)
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
