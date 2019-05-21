<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Service\APIClient\CatalogClient;
use App\Service\ImageService;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;

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
     * @param array $queries
     * @param string|null $model
     * @return object
     */
    protected function hydrateFromResponse(string $endpoint, array $queries = [], string $model = null)
    {
        $response = $this->arrayFromResponse($endpoint, $queries);

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            $model ?? $this->modelName,
            'xml'
        );
    }

    /**
     * @param string $endpoint
     * @param array $queries
     * @return Response
     */
    protected function arrayFromResponse(string $endpoint, array $queries = []): Response
    {
        return $this->api->get($endpoint, $queries);
    }


    /**
     * @param string $url
     * @param string $folderName
     * @return string
     */
    protected function saveLocalImage(string $url, string $folderName): string
    {
        try {
            return $this->imageService->getLocalPath($url, $folderName);
        } catch (ContextErrorException $exception) {
            // Log error
        }

        return $url;
    }
}
