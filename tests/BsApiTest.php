<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Prophecy\Argument;
use Prophecy\Prophet;
use Dncusmir\BsApi\BsApi;
use Laminas\Diactoros\RequestFactory;

class BsApiTest extends TestCase
{
    protected function setUp(): void
    {
        $this->prophet = new Prophet();
    }

    /** @test */
    public function test_fetch_response(): void
    {        
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];

        $client = $this->prophet->prophesize(ClientInterface::class);
        $client->sendRequest(
            Argument::that(function(RequestInterface $request) use ($apiKey): bool {
                self::assertEquals($request->getHeaderLine('authorization'), 'Bearer ' . $apiKey);
                self::assertEquals($request->getHeaderLine('Accept'), 'application/json');

                return true;
            })
        )
            ->shouldBeCalled()
            ->willReturn($this->prophet->prophesize(ResponseInterface::class)->reveal());
        
        $api = new BsApi($apiKey, $client->reveal(), new RequestFactory());
        $response = $api->fetchResponse('method', 'url', $headers);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /** @test */
    public function test_get_player_information(): void
    {
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];
        $tag = 'tag';

        $response = $this->prophet->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"player": "tag"}');

        $api = $this->prophet->prophesize(BsApi::class);
        $api->fetchResponse('GET', 'players/' . $tag, $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $bsapi = $api->reveal();

        $r = $bsapi->fetchResponse('GET', 'players/' . $tag, $headers);

        $this->assertInstanceOf(ResponseInterface::class, $r);
        $this->assertEquals(['player' => 'tag'], json_decode((string)$r->getBody(), true));
    }

    /** @test */
    public function test_get_player_battle_log(): void
    {
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];
        $tag = 'tag';

        $response = $this->prophet->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"battle": "log"}');

        $api = $this->prophet->prophesize(BsApi::class);
        $api->fetchResponse('GET', 'players/' . $tag . '/battlelog', $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $bsapi = $api->reveal();

        $r = $bsapi->fetchResponse('GET', 'players/' . $tag . '/battlelog', $headers);

        $this->assertInstanceOf(ResponseInterface::class, $r);
        $this->assertEquals(['battle' => 'log'], json_decode((string)$r->getBody(), true));
    }

    /** @test */
    public function test_get_club_information(): void
    {
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];
        $tag = 'tag';

        $response = $this->prophet->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"club": "tag"}');

        $api = $this->prophet->prophesize(BsApi::class);
        $api->fetchResponse('GET', 'clubs/' . $tag, $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $bsapi = $api->reveal();

        $r = $bsapi->fetchResponse('GET', 'clubs/' . $tag, $headers);

        $this->assertInstanceOf(ResponseInterface::class, $r);
        $this->assertEquals(['club' => 'tag'], json_decode((string)$r->getBody(), true));
    }

    /** @test */
    public function test_get_club_members(): void
    {
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];
        $tag = 'tag';

        $response = $this->prophet->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"club": "members"}');

        $api = $this->prophet->prophesize(BsApi::class);
        $api->fetchResponse('GET', 'clubs/' . $tag . '/members', $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $bsapi = $api->reveal();

        $r = $bsapi->fetchResponse('GET', 'clubs/' . $tag . '/members', $headers);

        $this->assertInstanceOf(ResponseInterface::class, $r);
        $this->assertEquals(['club' => 'members'], json_decode((string)$r->getBody(), true));
    }

    /** @test */
    public function test_get_player_rankings(): void
    {
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];
        $countryCode = 'countryCode';

        $response = $this->prophet->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"player": "rankings"}');

        $api = $this->prophet->prophesize(BsApi::class);
        $api->fetchResponse('GET', 'rankings/' . $countryCode . '/players', $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $bsapi = $api->reveal();

        $r = $bsapi->fetchResponse('GET', 'rankings/' . $countryCode . '/players', $headers);

        $this->assertInstanceOf(ResponseInterface::class, $r);
        $this->assertEquals(['player' => 'rankings'], json_decode((string)$r->getBody(), true));
    }

    /** @test */
    public function test_get_club_rankings(): void
    {
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];
        $countryCode = 'countryCode';

        $response = $this->prophet->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"club": "rankings"}');

        $api = $this->prophet->prophesize(BsApi::class);
        $api->fetchResponse('GET', 'rankings/' . $countryCode . '/clubs', $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $bsapi = $api->reveal();

        $r = $bsapi->fetchResponse('GET', 'rankings/' . $countryCode . '/clubs', $headers);

        $this->assertInstanceOf(ResponseInterface::class, $r);
        $this->assertEquals(['club' => 'rankings'], json_decode((string)$r->getBody(), true));
    }

    /** @test */
    public function test_get_brawler_rankings(): void
    {
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];
        $countryCode = 'countryCode';
        $brawlerId = 'brawlerId';

        $response = $this->prophet->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"brawler": "rankings"}');

        $api = $this->prophet->prophesize(BsApi::class);
        $api->fetchResponse('GET', 'rankings/' . $countryCode . '/brawlers/' . $brawlerId, $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $bsapi = $api->reveal();

        $r = $bsapi->fetchResponse('GET', 'rankings/' . $countryCode . '/brawlers/' . $brawlerId, $headers);

        $this->assertInstanceOf(ResponseInterface::class, $r);
        $this->assertEquals(['brawler' => 'rankings'], json_decode((string)$r->getBody(), true));
    }

    /** @test */
    public function test_get_brawlers(): void
    {
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];

        $response = $this->prophet->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"brawlers": "all"}');

        $api = $this->prophet->prophesize(BsApi::class);
        $api->fetchResponse('GET', 'brawlers', $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $bsapi = $api->reveal();

        $r = $bsapi->fetchResponse('GET', 'brawlers', $headers);

        $this->assertInstanceOf(ResponseInterface::class, $r);
        $this->assertEquals(['brawlers' => 'all'], json_decode((string)$r->getBody(), true));
    }

    /** @test */
    public function test_get_brawler_information(): void
    {
        $apiKey = 'api_key';
        $headers = [
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . $apiKey
        ];
        $brawlerId = 'brawlerId';

        $response = $this->prophet->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"brawler": "brawlerId"}');

        $api = $this->prophet->prophesize(BsApi::class);
        $api->fetchResponse('GET', 'brawler/' . $brawlerId, $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $bsapi = $api->reveal();

        $r = $bsapi->fetchResponse('GET', 'brawler/' . $brawlerId, $headers);

        $this->assertInstanceOf(ResponseInterface::class, $r);
        $this->assertEquals(['brawler' => 'brawlerId'], json_decode((string)$r->getBody(), true));
    }
}