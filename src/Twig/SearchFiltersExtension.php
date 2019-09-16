<?php
declare(strict_types=1);

namespace App\Twig;


use App\Model\Authority;
use App\Service\BreadcrumbBuilder;
use App\Service\NavigationService;
use App\Utils\BreadcrumbNavigation;
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
            new TwigFunction('get_value_by_field_name', [$this, 'getValueByFieldName']),
            new TwigFunction('check_value_exist', [$this, 'isValueExist']),
            new TwigFunction('sameInstance', [$this, 'sameInstance']),
            new TwigFunction('route_by_object', [$this, 'getRouteByObject']),
            new TwigFunction('breadcrumb_navigation', [$this, 'getBreadcrumb']),
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

    /**
     * @param string $key
     * @param $searchValue
     * @param array|null $array
     * @return bool
     */
    public function isValueExist(string $key,  $searchValue, array $array=null):bool
    {
        if (empty($array[$key])){
            return false;
        }

        foreach ($array[$key] as $index => $value){

             if ($value === $searchValue){
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

}

