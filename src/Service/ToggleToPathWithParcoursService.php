<?php
declare(strict_types=1);

namespace App\Service;
use App\Controller\SearchController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


final class ToggleToPathWithParcoursService
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
     * @param string $pathName
     * @param string $parcours
     * @param array $pathParameters
     * @return string
     */
    public function toggle(string $pathName, string $parcours, array $pathParameters=[]):string
    {
        if ($parcours === SearchController::GENERAL){
            return $this->routeCollection->generate($pathName, $pathParameters);
        }

        return $this->routeCollection->generate(sprintf($pathName.'_%s', 'parcours'), array_merge($pathParameters, ['parcours'=> $parcours]));

    }

}
