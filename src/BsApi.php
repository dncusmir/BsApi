<?php

declare(strict_types=1);

namespace Dncusmir\BsApi;

use Dncusmir\BsApi\Exceptions\ClientException;
use Dncusmir\BsApi\Exceptions\ResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Client for BrawlStars Api
 */
class BsApi extends AbstractApi
{
    /**
     * @var array<string, string>
     */
    protected $headers;

    /**
     * @param string                                    $apiKey             api key for BrawlStars
     * @param \Psr\Http\Client\ClientInterface          $httpClient
     * @param \Psr\Http\Message\RequestFactoryInterface $httpRequestFactory
     */
    public function __construct(
        string $apiKey,
        ClientInterface $httpClient,
        RequestFactoryInterface $httpRequestFactory
    ) {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient;
        $this->httpRequestFactory = $httpRequestFactory;

        $this->headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $this->apiKey,
        ];
    }

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
    public function fetchResponse(string $method, string $uri, array $headers = []): ResponseInterface
    {
        $request = $this->httpRequestFactory->createRequest($method, self::BASEURL . $uri);

        if (!empty($headers)) {
            foreach ($headers as $name => $value) {
                $request = $request->withHeader($name, $value);
            }
        }

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ClientException('Cannot send the request.');
        }

        return $response;
    }

    /**
     * Get player information for a specific tag
     * GET call to /players/{playerTag}
     *
     * @param string $playerTag
     *
     * @return array
     *
     * @throws \Dncusmir\BsApi\Exceptions\ResponseException
     */
    public function getPlayerInformation(string $playerTag): array
    {   
        /** @var \Psr\Http\Message\ResponseInterface */
        $response = $this->fetchResponse('GET', 'players/' . $playerTag, $this->headers);

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response->getStatusCode(). $response->getReasonPhrase());
        }

        /** @var array */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * Get log of recent battles for a player.
     * GET call to /players/{playerTag}/battlelog
     *
     * @param string $playerTag
     *
     * @return array
     *
     * @throws \Dncusmir\BsApi\Exceptions\ResponseException
     */
    public function getPlayerBattleLog(string $playerTag): array
    {
        $response = $this->fetchResponse('GET', 'players/' . $playerTag . '/battlelog', $this->headers);

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response->getStatusCode(). $response->getReasonPhrase());
        }

        /** @var array */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * Get club information.
     * GET call to /clubs/{clubTag}
     *
     * @param string $clubTag
     *
     * @return array
     *
     * @throws \Dncusmir\BsApi\Exceptions\ResponseException
     */
    public function getClubInformation(string $clubTag): array
    {
        $response = $this->fetchResponse('GET', 'clubs/' . $clubTag, $this->headers);

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response->getStatusCode(). $response->getReasonPhrase());
        }

        /** @var array */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * List club members.
     * GET call to /clubs/{clubTag}/members
     *
     * @param string $clubTag
     *
     * @return array
     *
     * @throws \Dncusmir\BsApi\Exceptions\ResponseException
     */
    public function getClubMembers(string $clubTag): array
    {
        $response = $this->fetchResponse('GET', 'clubs/' . $clubTag . '/members', $this->headers);

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response->getStatusCode(). $response->getReasonPhrase());
        }

        /** @var array */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * Get player rankings for a country or global rankings.
     * GET call to /rankings/{countryCode}/players
     *
     * @param string $countryCode Two letter country code, or 'global' for global rankings.
     *
     * @return array
     *
     * @throws \Dncusmir\BsApi\Exceptions\ResponseException
     */
    public function getPlayerRankings(string $countryCode): array
    {
        $response = $this->fetchResponse('GET', 'rankings/' . $countryCode . '/players', $this->headers);

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response->getStatusCode(). $response->getReasonPhrase());
        }

        /** @var array */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * Get club rankings for a country or global rankings.
     * GET call to /rankings/{countryCode}/clubs
     *
     * @param string $countryCode Two letter country code, or 'global' for global rankings.
     *
     * @return array
     *
     * @throws \Dncusmir\BsApi\Exceptions\ResponseException
     */
    public function getClubRankings(string $countryCode): array
    {
        $response = $this->fetchResponse('GET', 'rankings/' . $countryCode . '/clubs', $this->headers);

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response->getStatusCode(). $response->getReasonPhrase());
        }

        /** @var array */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * Get brawler rankings for a country or global rankings.
     * GET call to /rankings/{countryCode}/brawlers/{brawlerId}
     *
     * @param string $countryCode Two letter country code, or 'global' for global rankings.
     *
     * @return array
     *
     * @throws \Dncusmir\BsApi\Exceptions\ResponseException
     */
    public function getBrawlerRankings(string $countryCode, string $brawlerId): array
    {
        $response = $this->fetchResponse(
            'GET',
            'rankings/' . $countryCode . '/brawlers/' . $brawlerId,
            $this->headers
        );

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response->getStatusCode(). $response->getReasonPhrase());
        }

        /** @var array */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * Get list of available brawlers.
     * GET call to /brawlers
     *
     * @return array
     *
     * @throws \Dncusmir\BsApi\Exceptions\ResponseException
     */
    public function getBrawlers(): array
    {
        $response = $this->fetchResponse('GET', 'brawlers', $this->headers);

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response->getStatusCode(). $response->getReasonPhrase());
        }

        /** @var array */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * Get information about a brawler.
     * GET call to /brawlers/{brawlerId}
     *
     * @param string $brawlerId
     *
     * @return array
     *
     * @throws \Dncusmir\BsApi\Exceptions\ResponseException
     */
    public function getBrawlerInformation(string $brawlerId): array
    {
        $response = $this->fetchResponse('GET', 'brawler/' . $brawlerId, $this->headers);

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response->getStatusCode(). $response->getReasonPhrase());
        }

        /** @var array */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }
}
