<?php
declare(strict_types=1);

namespace App\Twig;


use App\Controller\SearchController;
use App\Service\ToggleToPathWithParcoursService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ToggleToPathWithParcours extends AbstractExtension
{

    /**
     * @var ToggleToPathWithParcoursService
     */
    private $parcoursService;

    /**
     * ToggleToPathWithParcours constructor.
     * @param ToggleToPathWithParcoursService $parcoursService
     */
    public function __construct(ToggleToPathWithParcoursService $parcoursService)
    {
        $this->parcoursService = $parcoursService;
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
        if ($parcours===null){
            $parcours=SearchController::GENERAL;
        }
        return $this->parcoursService->toggle($pathName, $parcours, $pathParameters);
    }
}
