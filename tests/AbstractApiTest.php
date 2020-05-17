<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Dncusmir\BsApi\AbstractApi;
use Psr\Http\Message\ResponseInterface;

class AbstractApiTest extends TestCase
{
    /**
     * @var BsApi\AbstractApi
     */
    private $testedClass;

    protected function setUp(): void
    {
        $this->testedClass = new class extends AbstractApi
        {
            protected function fetchResponse(
                string $method,
                string $uri,
                ?array $headers = []
            ): ResponseInterface {
                return new class extends ResponseInterface {
                };
            }
        };
    }

    /** @test */
    public function it_has_a_base_url(): void
    {
        $reflectionClass = new \ReflectionClass($this->testedClass);
        $this->assertArrayHasKey('BASEURL', $reflectionClass->getConstants());
    }

    /** @test */
    public function it_has_property_api_key(): void
    {
        $this->assertClassHasAttribute('apiKey', get_class($this->testedClass));
    }

    /** @test */
    public function it_has_a_http_client(): void
    {
        $this->assertClassHasAttribute('httpClient', get_class($this->testedClass));
    }

    /** @test */
    public function it_has_a_request_factory(): void
    {
        $this->assertClassHasAttribute('httpRequestFactory', get_class($this->testedClass));
    }
}
