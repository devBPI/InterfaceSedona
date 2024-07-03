<?php

namespace App\Provider;

use League\OAuth2\Client\Provider\GenericProvider;

class BpiOAuth2Provider extends GenericProvider
{
    /**
     * Returns the HTTP method used to fetch access tokens.
     *
     * @return string
     */
    protected function getAccessTokenMethod()
    {
        return self::METHOD_GET;
    }
}
