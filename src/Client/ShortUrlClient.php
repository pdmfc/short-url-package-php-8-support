<?php

namespace Pdmfc\Shorturl\Client;

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
     * @param string $shortUrlCode
     * @return mixed
     */
    public function getUrl(string $shortUrlCode)
    {
        if (!$shortUrlCode) {
            return response('Short Url Code is Required', Response::HTTP_FORBIDDEN);
        }

        return $this->get("getShortUrl/{$shortUrlCode}");
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function createUrl(array $params = [])
    {
        return $this->post('createShortUrl', $params);
    }

    /**
     * @param string $shortUrlCode
     * @param array $params
     * @return mixed
     */
    public function updateUrl(string $shortUrlCode, array $params)
    {
        if (!$shortUrlCode) {
            return response('Short Url Code is Required', Response::HTTP_FORBIDDEN);
        }

        return $this->post("changeShortUrl/{$shortUrlCode}", $params);
    }

    /**
     * @param $shortUrlCode
     * @return mixed
     */
    public function deleteUrl(string $shortUrlCode)
    {
        if (!$shortUrlCode) {
            return response('Short Url Code is Required', Response::HTTP_FORBIDDEN);
        }

        return $this->delete("deleteShortUrl/{$shortUrlCode}");
    }

    /**
     * @param string $uri
     * @param array $params
     * @return mixed
     */
    protected function get(string $uri, array $params = [])
    {
        return $this->sendRequest(Request::METHOD_GET, $uri, $params);
    }

    /**
     * @param string $uri
     * @param array $params
     * @return mixed
     */
    protected function post(string $uri, array $params = [])
    {
        return $this->sendRequest(Request::METHOD_POST, $uri, [
            RequestOptions::FORM_PARAMS => $params
        ]);
    }

    /**
     * @param string $uri
     * @return mixed
     */
    protected function delete(string $uri)
    {
        return $this->sendRequest(Request::METHOD_DELETE, $uri);
    }

    /**
     * @param string $requestMethod
     * @param string $uri
     * @param array $params
     * @return mixed
     */
    protected function sendRequest(string $requestMethod, string $uri, array $params = [])
    {
        try {
            $response = $this->client->request($requestMethod, trim($uri, '/'), $params);
        } catch (GuzzleException $exception) {
            $response = $exception->getResponse();
        }

        return $this->decodedResponse($response);
    }

    /**
     * @param ResponseInterface|null $response
     * @return mixed
     */
    protected function decodedResponse(?ResponseInterface $response)
    {
        $response = json_decode(optional(optional($response)->getBody())->getContents());

        return $response;
    }

    /**
     * @return string
     */
    public function baseUri(): string
    {
        return rtrim(config('shorturl.base_url'), '/') . '/api/';
    }
}
