<?php
namespace App\Service;

use GuzzleHttp\ClientInterface;

class HttpClientService
{
	private $client;

	public function __construct(ClientInterface $client)
	{
		$this->client = $client;
	}

	public function disconnectOAuth()
	{
		// URL interne que tu souhaites appeler
		$url = 'https://auth-test.bpi.fr/?logout=1';

		$response = $this->client->request('GET', $url);
        
		if ($response->getStatusCode() === 200)
		{
			return json_decode($response->getBody()->getContents(), true);
		}

		throw new \Exception('Failed to fetch data from the API');
	}
}
