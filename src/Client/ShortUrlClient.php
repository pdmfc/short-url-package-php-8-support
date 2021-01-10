<?php

namespace Pdmfc\Shorturl\Client;

use stdClass;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortUrlClient
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
                'base_uri' => $this->baseUri(),
                'headers' => [
                    'APPID' => config('shorturl.auth.id'),
                    'APPKEY' => config('shorturl.auth.token'),
                    'Content-Type'  => 'application/json',
                ]
            ]
        );
    }

    /**
     * Fetches the short url data.
     *
     * @param string $uri
     * @return \Illuminate\Http\Response|null|stdClass
     */
    public function getUrl(string $uri)
    {
        if (!$uri) {
            return response('Short Url Code is Required', Response::HTTP_FORBIDDEN);
        }

        return $this->get("getShortUrl/{$uri}");
    }

    /**
     * Creates a short url.
     *
     * @param array $params
     * @return null|stdClass
     */
    public function createUrl(array $params = []): ?stdClass
    {
        return $this->post('createShortUrl', $params);
    }

    /**
     * Updates the short url data.
     *
     * @param string $uri
     * @param array $params
     * @return \Illuminate\Http\Response|null|stdClass
     */
    public function updateUrl(string $uri, array $params)
    {
        if (!$uri) {
            return response('Short Url Code is Required', Response::HTTP_FORBIDDEN);
        }

        return $this->post("changeShortUrl/{$uri}", $params);
    }

    /**
     * Deletes the short url.
     *
     * @param $uri
     * @return \Illuminate\Http\Response|null|stdClass
     */
    public function deleteUrl(string $uri)
    {
        if (!$uri) {
            return response('Short Url Code is Required', Response::HTTP_FORBIDDEN);
        }

        return $this->delete("deleteShortUrl/{$uri}");
    }

    /**
     * Http client wrapper for sending a "GET" request.
     *
     * @param string $uri
     * @param array $params
     * @return null|stdClass
     */
    protected function get(string $uri, array $params = []): ?stdClass
    {
        return $this->sendRequest(Request::METHOD_GET, $uri, $params);
    }

    /**
     * Http client wrapper for sending a "POST" request.
     *
     * @param string $uri
     * @param array $params
     * @return null|stdClass
     */
    protected function post(string $uri, array $params = []): ?stdClass
    {
        return $this->sendRequest(Request::METHOD_POST, $uri, [
            RequestOptions::FORM_PARAMS => $params
        ]);
    }

    /**
     * Http client wrapper for sending a "DELETE" request.
     *
     * @param string $uri
     * @return null|stdClass
     */
    protected function delete(string $uri): ?stdClass
    {
        return $this->sendRequest(Request::METHOD_DELETE, $uri);
    }

    /**
     * Handles the http client request.
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     * @return null|stdClass
     */
    protected function sendRequest(string $method, string $uri, array $params = []): ?stdClass
    {
        try {
            $response = $this->client->request($method, trim($uri, '/'), $params);
        } catch (GuzzleException $exception) {
            $response = $exception->getResponse();
        }

        return $this->decodedResponse($response);
    }

    /**
     * @param ResponseInterface|null $response
     * @return null|stdClass
     */
    protected function decodedResponse(?ResponseInterface $response): ?stdClass
    {
        $response = json_decode(optional(optional($response)->getBody())->getContents());

        return $response;
    }

    /**
     * The ShortUrlClient base URI.
     *
     * @return string
     */
    public function baseUri(): string
    {
        return rtrim(config('shorturl.base_url'), '/') . '/api/';
    }
}
