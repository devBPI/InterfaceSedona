<?php
declare(strict_types=1);

namespace App\Twig;


use App\Service\TraitSlugify;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class StringExtension
 * @package App\Twig
 */
class StringExtension extends AbstractExtension
{
    use TraitSlugify;

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('snake', [$this, 'toSnakeCase']),
            new TwigFilter('slugify', [$this, 'toSlugify']),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'string_extension';
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
