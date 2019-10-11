<?php

namespace App\Service\APIClient;


use App\Model\ErrorApiResponse;
use App\Model\Exception\ApiException;
use App\Model\Exception\ErrorAccessApiException;
use App\Model\Exception\NoResultException;
use EightPoints\Bundle\GuzzleBundle\Log\Logger;
use EightPoints\Bundle\GuzzleBundle\Log\LoggerInterface;
use EightPoints\Bundle\GuzzleBundle\Middleware\LogMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\MessageFormatter;
use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class CatalogClient
 * @package App\Service\APIClient
 */
class CatalogClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * CatalogClient constructor.
     * @param string $api_path
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string|UriInterface $uri
     * @param array|null $query
     * @return mixed
     */
    public function get($uri, array $query = null)
    {
        try {
            $options = [
                'query' => $query,
            ];

            $response = $this->client->get($uri, $options);

            $this->manageResponseCode($response);

            return $response;
        } catch (ConnectException $exception) {
            throw new ErrorAccessApiException();
        } catch (ClientException|ServerException $exception) {
            $this->formatException($exception);
        }
    }


    /**
     * @param BadResponseException $exception
     */
    private function formatException(BadResponseException $exception)
    {
        switch ($exception->getCode()) {
            case 403:
                throw new AccessDeniedException('Access Denied', $exception);
            case 404:
                throw new NoResultException();
            case 400:
            case 500:
                throw new ApiException($exception->getMessage());
            default:
                $error_message = new ErrorApiResponse(json_decode($exception->getResponse()->getBody()->getContents()));
                throw new ApiException($error_message->getMessage());
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    private function manageResponseCode(\Psr\Http\Message\ResponseInterface $response)
    {
        switch ($response->getStatusCode()) {
            case 403:
                throw new AccessDeniedException('Access Denied');
            case 404:
                throw new NotFoundHttpException();
            default:
                break;
        }
    }

}
