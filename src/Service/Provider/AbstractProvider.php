<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\NoticeMappedAuthority;
use App\Model\Status;
use App\Service\APIClient\CatalogClient;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;

/**
 * Class AbstractProvider
 * @package App\Service\Provider
 */
abstract class AbstractProvider implements ApiProviderInterface
{
    /** @var string */
    protected $modelName;

    /** @var CatalogClient */
    protected $api;

    /** @var Serializer */
    protected $serializer;

    /**
     * AbstractProvider constructor.
     * @param CatalogClient $api
     * @param SerializerInterface $serializer
     */
    public function __construct(CatalogClient $api, SerializerInterface $serializer)
    {
        $this->api = $api;
        $this->serializer = $serializer;
    }

    /**
     * @param string $endpoint
     * @param array $queries
     * @param string|null $model
     * @return mixed
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
}
