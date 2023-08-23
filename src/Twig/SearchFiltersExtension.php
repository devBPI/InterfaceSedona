<?php
declare(strict_types=1);

namespace App\Twig;


use App\Entity\ParcoursLinkInterface;
use App\Entity\ThemeLevel;
use App\Model\Form\ExportNotice;
use App\Model\Search\FilterFilter;
use App\Model\Search\FacetFilter;
use App\Service\NavigationService;
use App\Service\Provider\EssentialsResourceProvider;
use App\WordsList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
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
    private $filterQueries;
    /**
     * @var array
     */
    private $facetQueries;

    /**
     * @var EssentialsResourceProvider $essentialsResourceProvider;
     */
    private EssentialsResourceProvider $essentialsResourceProvider;
    private RouterInterface $router;

    /**
     * SearchFiltersExtension constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, EssentialsResourceProvider  $essentialsResourceProvider, RouterInterface $router)
    {
        $this->masterRequest = $requestStack->getMasterRequest();
        if ($requestStack->getMasterRequest() instanceof Request) {
            $this->facetQueries = $requestStack->getMasterRequest()->get(FacetFilter::QUERY_NAME, []);
        }
        $this->essentialsResourceProvider = $essentialsResourceProvider;
        $this->router = $router;
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
            new TwigFunction('pdf_occurence_data', [$this, 'getPdfOccurenceData']),
            new TwigFunction('cut_filter_from_search', [$this, 'cutfilterFromSearch']),
            new TwigFunction('is_advanced_search', [$this, 'isAdvancedSaerch']),
            new TwigFunction('add_parameter_url', [$this, 'addParameterUrl']),
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
    public function getPdfOccurence($object, string $method, string $label, string $format = ExportNotice::FORMAT_PDF, $glue = ' ; '): ?string
    {
        if (!method_exists($object, $method)) {
            return null;
        }
        return $this->getPdfOccurenceData($object->{$method}(),$label, $format, $glue);
    }

    public function getPdfOccurenceData($data, string $label, string $format = ExportNotice::FORMAT_PDF, $glue = ' ; '): ?string
    {
        if (!empty($data)) {
            $payload = "";
            if (is_array($data)) {
                $payload .= implode($glue,  array_filter($data));
            } elseif ((string) $data !== '') {
                $payload .= $data;
            }

            if (!empty($label)) {
                if ($format === ExportNotice::FORMAT_TEXT) {
                    return sprintf("%s : %s\n", $label, $payload);
                } else {
                    return sprintf("<li><span>%s :</span> %s</li>", $label, $payload);
                }
            }elseif($label ==''){
                if ($format === ExportNotice::FORMAT_TEXT) {
                    return sprintf("%s\n", $payload);
                } else {
                    return sprintf("<li>%s</li>", $payload);
                }
            }

            if ($format === ExportNotice::FORMAT_TEXT) {
                $payload .= "\n";
            }

            return $payload;
        }

        return null;
    }


    public function cutfilterFromSearch($type, $value){
        $url = $this->masterRequest->getRequestUri();
        $payload =    explode('&', urldecode($url));
        $link = $payload[0];

        unset($payload[0]);
        try {
            $t = array_filter($payload, function ($element)use($value, $type){
                if(strpos($element, 'facets')===false){
                //    return true;
                }
                if ($type === 'date_publishing' && strpos($element, $type)>0){
                   // return false;
                }
                list($ftype, $fvalue) = explode('=', $element);

                return strpos($element, $type) === false || strpos($value, $fvalue)===false;
            });

        }catch (\Exception $e ){
            throw new \Exception('an error was occurred when cutting parameters from url search criteria %', $e->getMessage());
        }
        if (count($t)===count($payload)){
            return "";
        }
        if(count($t)>0){
            $url = sprintf('%s&%s', $link, implode('&', $t));
        }else{
            $url = $link;
        }
        
        if ($type === 'date_publishing'){
            $re = '/(facets\[date_publishing\]\[\]\=[0-9]{4}\&)/m';
            $result = preg_replace($re, '', $url);
            $re = '/(facets\[date_publishing\]\[\]\=[0-9]{4})/m';
            $result = preg_replace($re, '', $result);

            return $result;
        }

        return $url;
    }

    /**
     * @param $object
     * @param string $locale
     * @return string|null
     */
    public function addParameterUrl(ParcoursLinkInterface $object, string $locale): ?string
    {
        try {
            $url = $this->router->generate('advanced_search_parcours', ['parcours' => $object->getParcours()]);
            if ($url === null){
                return null;
            }
            $code = $object->getCode();
            if (empty($code)) {
                return null;
            }

            $codeEss = $this->essentialsResourceProvider->getEssentialResource($code);
            return  $url.'?'.$codeEss;
        } catch (\Exception $exception) { }
        return null;
    }

}
