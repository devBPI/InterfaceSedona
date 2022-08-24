<?php
declare(strict_types=1);

namespace App\Twig;


use App\Controller\SearchController;
use App\Model\Search\ObjSearch;
use App\WordsList;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ToggleToPathWithParcours extends AbstractExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $routeCollection;

    /**
     * ToggleToPathWithParcours constructor.
     * @param UrlGeneratorInterface $routeCollection
     */
    public function __construct(UrlGeneratorInterface $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('toggle_to_path_parcours', [$this, 'togglePathPacours']),
        ];
    }

    /**
     * @param string $pathName
     * @param string $parcours
     * @param array $pathParameters
     * @return string
     */
    public function togglePathPacours(string $pathName, string $parcours=null, array $pathParameters=[]):string
    {
        if ($parcours === SearchController::GENERAL || $parcours===null ){
            return $this->routeCollection->generate($pathName, $pathParameters);
        }

        return $this
            ->routeCollection
            ->generate(sprintf($pathName.'_%s', 'parcours'),
                array_merge($pathParameters, [ObjSearch::PARAM_PARCOURS_NAME=> $parcours]))
            ;
    }
}
