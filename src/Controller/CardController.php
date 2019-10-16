<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 15/10/19
 * Time: 17:33
 */

namespace App\Controller;


use App\Model\Search\SearchQuery;
use App\Service\NavigationService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class CardController extends AbstractController
{
    /**
     * @var NavigationService
     */
    private $navigationService;
    /**
     * @var SerializerInterface
     */
    private $serializer;


    /**
     * CardController constructor.
     * @param NavigationService $navigationService
     * @param SerializerInterface $serializer
     */
    public function __construct(NavigationService $navigationService, SerializerInterface $serializer)
    {
        $this->navigationService = $navigationService;
        $this->serializer = $serializer;
    }

    /**
     * @param string $permalink
     * @param string $searchToken
     * @param string $searchTokenValue
     * @param $classType
     * @return NavigationService
     */
    protected function buildNavigationService(string $permalink,string $searchToken,  string $searchTokenValue, $classType):NavigationService
    {
        return $this->navigationService->build($permalink, $this->serializer->deserialize($searchTokenValue, SearchQuery::class, 'json'), $searchToken, $classType);
    }
}
