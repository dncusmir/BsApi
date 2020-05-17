<?php

declare(strict_types=1);

namespace Dncusmir\BsApi;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractApi
{
    /**
     * @var string Base Url for the api
     */
    public const BASEURL = 'https://api.brawlstars.com/v1/';

    /**
     * @var string BrawlStars api key
     */
    protected $apiKey;

    /**
     * @var \Psr\Http\Client\ClientInterface Http Client for api calls
     */
    protected $httpClient;

    /**
     * @var \Psr\Http\Message\RequestFactoryInterface Factory to create the request for api calls
     */
    protected $httpRequestFactory;

    /**
     * Fetch response from api call to $uri
     *
     * @param string                $method  method for the call (GET, etc)
     * @param string                $uri     uri endpoint
     * @param array<string, string> $headers headers used for the call
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Dncusmir\BsApi\Exceptions\ClientException
     */
    abstract protected function fetchResponse(
        string $method,
        string $uri,
        array $headers = []
    ): ResponseInterface;
}
