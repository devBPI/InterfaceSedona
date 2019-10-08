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
    const TIMEOUT = 10;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var MessageFormatter
     */
    private $formatter;

    /**
     * @var string
     */
    private $api_path;

    /**
     * CatalogClient constructor.
     * @param string $api_path
     * @param MessageFormatter $formatter
     * @param Logger $logger
     */
    public function __construct(
        string $api_path,
        MessageFormatter $formatter,
        Logger $logger
    ) {
        $this->api_path = $api_path;
        $this->formatter = $formatter;
        $this->logger = $logger;
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

            $response = $this->getClient()->get($uri, $options);

            $this->manageResponseCode($response);

            return $response;
        } catch (ConnectException $exception) {
            throw new ErrorAccessApiException();
        } catch (ClientException|ServerException $exception) {
            $this->formatException($exception);
        }
    }

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        if ($this->client instanceof Client === false) {
            $stack = \GuzzleHttp\HandlerStack::create();

            // Data collector
            if ($this->logger instanceof LoggerInterface) {
                $log = new LogMiddleware($this->logger, $this->formatter);
                $stack->push($log->log(), 'logger');
            }

            $headers = ['Content-Type' => "application/xml"];

            $this->client = new Client(
                [
                    'base_uri' => $this->api_path."/",
                    'headers' => $headers,
                    'timeout' => self::TIMEOUT,
                    'handler' => $stack,
                ]
            );
        }

        return $this->client;
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
