<?php

namespace App\Service\Provider;

use App\Service\APIClient\CatalogClient;
use App\Service\ImageService;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AbstractProvider
 * @package App\Service\Provider
 */
abstract class AbstractProvider implements ApiProvider
{
    /** @var string */
    protected $modelName;

    /** @var CatalogClient */
    protected $api;

    /** @var Serializer */
    protected $serializer;

    /** @var ImageService */
    private $imageService;

    /**
     * AbstractProvider constructor.
     * @param CatalogClient $api
     * @param ImageService $imageService
     * @param SerializerInterface $serializer
     */
    public function __construct(CatalogClient $api, ImageService $imageService, SerializerInterface $serializer)
    {
        $this->api = $api;
        $this->imageService = $imageService;
        $this->serializer = $serializer;
    }

    /**
     * @param string $endpoint
     * @param array $groups
     * @return object
     */
    protected function hydrateFromResponse(string $endpoint, array $groups = [])
    {
        $jsonResponse = $this->arrayFromResponse($endpoint);

        return $this->serializer->deserialize(
            $jsonResponse->getBody()->getContents(),
            $this->modelName,
            'xml',
            ['xml_root_node_name' => 'carousel']
        );
    }

    /**
     * @param string $endpoint
     * @return Response
     */
    protected function arrayFromResponse(string $endpoint): Response
    {
        return $this->api->get($endpoint);
    }


    /**
     * @param string $url
     * @param string $folderName
     * @return string
     */
    protected function saveLocalImage(string $url, string $folderName): string
    {
        return $this->imageService->getLocalPath($url, $folderName);
    }
}
