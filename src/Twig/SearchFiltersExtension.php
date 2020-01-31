<?php
declare(strict_types=1);

namespace App\Twig;


use App\Model\Search\FacetFilter;
use App\Service\NavigationService;
use App\WordsList;
use Symfony\Component\HttpFoundation\Request;
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
     * @var Request
     */
    private $masterRequest;
    /**
     * @var array
     */
    private $facetQueries;

    /**
     * SearchFiltersExtension constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->masterRequest = $requestStack->getMasterRequest();
        if ($requestStack->getMasterRequest() instanceof Request) {
            $this->facetQueries = $requestStack->getMasterRequest()->get(FacetFilter::QUERY_NAME, []);
        }
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('search_words', [$this, 'getSearchWords']),
            new TwigFunction('is_search_word', [$this, 'isSearchWord']),
            new TwigFunction('words_operators', [$this, 'getSearchOperators']),
            new TwigFunction('check_value_in_facet', [$this, 'isValueExistInFacetQueries']),
            new TwigFunction('min_facet_value', [$this, 'getMinValueOfFacetQueries']),
            new TwigFunction('max_facet_value', [$this, 'getMaxValueOfFacetQueries']),
            new TwigFunction('route_by_object', [$this, 'getRouteByObject']),
            new TwigFunction('pdf_occurence', [$this, 'getPdfOccurence']),

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
     * @param string $word
     * @return bool
     */
    public function isSearchWord(string $word): bool
    {
        return in_array($word, $this->getSearchWords(), true);
    }

    /**
     * @param string $type
     * @return array
     */
    public function getSearchWords(string $type=WordsList::SEARCH_TYPE_DEFAULT): array
    {
        return WordsList::getWords($type, $this->masterRequest->get('parcours', WordsList::THEME_DEFAULT));
    }

    /**
     * @return array
     */
    public function getSearchOperators(): array
    {
        return WordsList::$operators;
    }

    /**
     * @param string $key
     * @param string $searchValue
     * @return bool
     */
    public function isValueExistInFacetQueries(string $key, string $searchValue): bool
    {
        if (!array_key_exists($key, $this->facetQueries)) {
            return false;
        }

        return count(array_filter($this->facetQueries[$key], function ($item) use ($searchValue) {
            return $item === $searchValue;
        })) === 1;
    }

    /**
     * @param string $key
     * @param string|null $default_value
     * @return null|string
     */
    public function getMinValueOfFacetQueries(string $key, string $default_value = null): ?string
    {
        if (!array_key_exists($key, $this->facetQueries) || count($this->facetQueries[$key]) === 0 ) {
            return $default_value;
        }

        return min($this->facetQueries[$key]);
    }

    /**
     * @param string $key
     * @param string $default_value
     * @return null|string
     */
    public function getMaxValueOfFacetQueries(string $key, string $default_value = null): ?string
    {
        if (!array_key_exists($key, $this->facetQueries) || count($this->facetQueries[$key]) === 0 ) {
            return $default_value;
        }

        return max($this->facetQueries[$key]);
    }

    /**
     * @param mixed $object
     * @return string
     */
    public function getRouteByObject($object)
    {
        return NavigationService::getRouteByObject($object);
    }

    /**
     * @param mixed $object
     * @param string $method
     * @param string $label
     * @param string $format
     * @return string
     */
    public function getPdfOccurence($object, string $method, string $label, string $format = 'pdf'): ?string
    {
        $payload = "";
        if (method_exists($object, $method) && !empty($object->{$method}())) {
            if (is_array($object->{$method}())) {
                $payload .= implode(' ; ', $object->{$method}());
            } elseif (!empty($object->{$method}()) && ((string) $object->{$method}()) !== '') {
                $payload .= $object->{$method}();
            }

            if (!empty($label)) {
                if ($format === 'pdf') {
                    return sprintf("<li>%s : %s</li>", $label, $payload);
                } elseif ($format === 'txt') {
                    return sprintf("%s : %s\n", $label, $payload);
                }
            }

            if ($format === 'txt') {
                $payload .= "\n";
            }

            return $payload;
        }

        return null;
    }

}

