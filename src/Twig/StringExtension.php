<?php
declare(strict_types=1);

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class StringExtension
 * @package App\Twig
 */
class StringExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('snake', [$this, 'toSnakeCase']),
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

}
