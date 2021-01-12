<?php

namespace Pdmfc\Shorturl\Tests;

use Illuminate\Support\Str;
use Pdmfc\Shorturl\Client\ShortUrlClient;
use Symfony\Component\HttpFoundation\Response;

class ShortUrlClientTest extends TestCase
{
    /**
     * @var ShortUrlClient
     */
    private $shortUrlClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shortUrlClient = new ShortUrlClient();
    }

    /** @test */
    public function it_returns_forbidden_status_code_when_searching_for_an_empty_short_url(): void
    {
        $response = $this->shortUrlClient->getUrl('');

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /** @test */
    public function it_returns_forbidden_status_code_when_sending_an_empty_short_url_to_update(): void
    {
        $response = $this->shortUrlClient->updateUrl('', []);

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /** @test */
    public function it_returns_forbidden_status_code_when_sending_an_empty_short_url_to_delete(): void
    {
        $response = $this->shortUrlClient->deleteUrl('');

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /** @test */
    public function does_not_return_an_ID_if_short_url_does_not_exist(): void
    {
        $response = $this->shortUrlClient->getUrl($shortUrl = '-1');

        self::assertObjectHasAttribute('message', $response);
        self::assertObjectNotHasAttribute('Id', $response);
    }

    /** @test */
    public function it_returns_short_url_data_if_exists(): void
    {
        $response = $this->shortUrlClient->getUrl($shortUrl = 'tq');

        self::assertObjectHasAttribute('Id', $response);
        self::assertNotNull($response->Id);

        self::assertObjectHasAttribute('shortUrl', $response);
        self::assertNotNull($response->shortUrl);

        self::assertObjectHasAttribute('qr_code', $response);
        self::assertNotNull($response->qr_code);
    }

    /** @test */
    public function it_creates_a_short_url_with_all_possible_params_provided(): void
    {
        $customString = Str::random();

        $params = [
            'domainUrl' => 'https://s.pdm.pt',
            'originalUrl' => 'http://www.phpunit-test-short-url-creation.com',
            'liveTime' => 0,
            'active' => true,
            'shortUrl' => $customString,
        ];

        $response = $this->shortUrlClient->createUrl($params);

        self::assertObjectHasAttribute('Id', $response);
        self::assertNotNull($response->Id);

        self::assertObjectHasAttribute('shortUrl', $response);
        self::assertNotNull($response->shortUrl);
        self::assertEquals("https://s.pdm.pt/{$customString}", $response->shortUrl);

        self::assertObjectHasAttribute('qrCode', $response);
        self::assertNotNull($response->qrCode);
    }

    /** @test */
    public function it_updates_a_short_url(): void
    {
        $customString = Str::random();

        $params = [
            'domainUrl' => 'https://s.pdm.pt',
            'originalUrl' => 'http://www.phpunit-test-short-url-creation.com',
            'liveTime' => 0,
            'active' => true,
            'shortUrl' => $customString,
        ];

        $this->shortUrlClient->createUrl($params);

        $this->shortUrlClient->updateUrl($customString, [
            'shortUrl' => $customStringUpdated = Str::random()
        ]);

        $searchResponseAfterUpdate = $this->shortUrlClient->getUrl($customStringUpdated);

        self::assertEquals("https://s.pdm.pt/{$customStringUpdated}", $searchResponseAfterUpdate->shortUrl);
    }

    /** @test */
    public function it_deletes_a_short_url(): void
    {
        $customString = Str::random();

        $params = [
            'domainUrl' => 'https://s.pdm.pt',
            'originalUrl' => 'http://www.phpunit-test-short-url-creation.com',
            'liveTime' => 0,
            'active' => true,
            'shortUrl' => $customString,
        ];

        $this->shortUrlClient->createUrl($params);

        $response = $this->shortUrlClient->deleteUrl($customString);

        self::assertIsObject($response);
        self::assertObjectHasAttribute('message', $response);
        self::assertEquals("Code $customString was deleted with success", $response->message);
    }
}
