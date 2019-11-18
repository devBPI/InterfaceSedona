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
            new TwigFilter('slugify', [$this, 'toSlugify']),
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
     * @return string
     */
    public function toSlugify(string $text) :string
    {
        return $this->slugify($text);
    }

}
