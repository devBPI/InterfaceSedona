<?php

namespace App\Service\APIClient;


use App\Model\ErrorApiResponse;
use App\Model\Exception\ApiException;
use App\Model\Exception\ErrorAccessApiException;
use App\Model\Exception\NoResultException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class CatalogClient
 *
 * @package App\Service\APIClient
 */
class CatalogClient
{
    private const AUTH_KEY_HEADER = 'AuthOrigin';
    private const DEFAULT_AUTH_VALUE = 'INTERNET';

    /**
     * @var Client
     */
    private $client;

    private ?RequestStack $request;

    /**
     * CatalogClient constructor.
     *
     * @param ClientInterface $client
     * @param RequestStack    $requestStack
     */
    public function __construct(ClientInterface $client, RequestStack $requestStack)
    {
        $this->client = $client;
        $this->request = $requestStack;
    }

    /**
     * @param string|UriInterface $uri
     * @param array|null          $query
     *
     * @return ResponseInterface
     */
    public function get($uri, array $query = null)
    {
        try {
            $options = [
                'query'   => $query,
                'headers' => $this->getHeaders(),
            ];

            $response = $this->client->get($uri, $options);

            $this->manageResponseCode($response);

            return $response;
        }
        catch (ConnectException $exception) {
            throw new ErrorAccessApiException(ErrorAccessApiException::MESSAGE, 500, $exception);
        } catch (ClientException|ServerException $exception) {
            if ($exception->getCode()===410){
                throw new NoResultException($exception->getMessage(), $exception->getCode(), $exception);
            }

            $this->formatException($exception);
        }
    }

    /**
     * @return array
     */
    private function getHeaders(): array
    {
        $authKey = self::DEFAULT_AUTH_VALUE;
        if ($this->request->getMasterRequest() instanceof Request) {
            $authKey = $this->request->getMasterRequest()->headers->get(self::AUTH_KEY_HEADER, self::DEFAULT_AUTH_VALUE);
        }
        return [
            self::AUTH_KEY_HEADER => $authKey,
        ];
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
