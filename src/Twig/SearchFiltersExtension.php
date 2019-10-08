<?php
declare(strict_types=1);

namespace App\Twig;


use App\Service\NavigationService;
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
            new TwigFunction('is_search_word', [$this, 'isSearchWord']),
            new TwigFunction('words_operators', [$this, 'getSearchOperators']),
            new TwigFunction('get_value_by_field_name', [$this, 'getValueByFieldName']),
            new TwigFunction('check_value_exist', [$this, 'isValueExist']),
            new TwigFunction('sameInstance', [$this, 'sameInstance']),
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
     * @return array
     */
    public function getSearchWords(): array
    {
        return WordsList::$words[$this->requestStack->getMasterRequest()->get('thematic', WordsList::THEME_DEFAULT)];
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
                array_key_exists($field, $context_queries[$index])
            ) {
                return $context_queries[$index][$field];
            }
        } elseif (array_key_exists($field, $context_queries)) {
            return $context_queries[$field];
        }

        return null;
    }

    /**
     * @param string $key
     * @param $searchValue
     * @param array|null $array
     * @return bool
     */
    public function isValueExist(string $key, $searchValue, array $array = null): bool
    {
        if (empty($array[$key])) {
            return false;
        }

        foreach ($array[$key] as $index => $value) {

            if ($value === $searchValue) {
                return true;
            }
        }

        return false;
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
     * @param $object
     * @return string
     */
    public function getRouteByObject($object)
    {
        return NavigationService::getRouteByObject($object);
    }

    /**
     * @param $object
     * @param $method
     * @param $label
     * @param string $format
     * @return string
     */
    public function getPdfOccurence($object, $method, $label, $format='pdf'){

        $payload = "";
        if (method_exists($object, $method) && $object->{$method}() ){
            if (is_array($object->{$method}())){
                foreach ($object->{$method}() as $value){
                    $payload .= $value. ' ';
                }

            }elseif (!empty($object->{$method}())  && $object->{$method}()&& $label){
                $payload .= $object->{$method}();
            }

            if (!empty($label) ){
                if ( $format=='pdf'){
                    return sprintf("<li> %s : %s</li>", $label, $payload);
                }elseif ($format=='txt'){
                    return sprintf("%s : %s \n", $label, $payload);
                }
            }

            return $payload;
        }

        return "";
    }
}

