<?php
declare(strict_types=1);

namespace App\Twig;


use App\WordsList;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class SearchFiltersExtension
 * @package App\Twig
 */
class SearchFiltersExtension extends AbstractExtension
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * SearchFiltersExtension constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('search_words', [$this, 'getSearchWords']),
            new TwigFunction('words_operators', [$this, 'getSearchOperators']),
            new TwigFunction('get_value_by_field_name', [$this, 'getValueByFieldName'])
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'search_filters_extension';
    }

    /**
     * @return array
     */
    public function getSearchWords(): array
    {
        return WordsList::$words[$this->requestStack->getMasterRequest()->get('thematic', WordsList::THEME_DEFAULT)];
    }

    /**
     * @return array
     */
    public function getSearchOperators(): array
    {
        return WordsList::$operators;
    }

    /**
     * @param string $context
     * @param string $field
     * @param int|null $index
     * @return null|string
     */
    public function getValueByFieldName(string $context, string $field, $index = null): ?string
    {
        $context_queries = $this->requestStack->getMasterRequest()->get($context, []);
        if ($index !== null) {
            if (
                is_array($context_queries) &&
                array_key_exists($index, $context_queries) &&
                array_key_exists($index, $context_queries[$index])
            ) {
                return $context_queries[$index][$field];
            }
        } elseif (array_key_exists($field, $context_queries)) {
            return $context_queries[$field];
        }

        return null;
    }
}
