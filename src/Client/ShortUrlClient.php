<?php

namespace Pdmfc\Shorturl\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ShortUrlClient
{
    private $client;

    /**
     * ShortUrlClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('shorturl.base_url'),
            'headers' => [
                'APPID' => config('shorturl.auth.id'),
                'APPKEY' => config('shorturl.auth.token'),
                'Content-Type'  => 'application/json',
            ]
        ]);
    }

    /**
     * Get Short Url by Code
     *
     * @param $shortUrlCode
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUrl($shortUrlCode)
    {
        if (!$shortUrlCode) {
            return response('Short Url Code is Required', 403);
        }

        try {
            $response = $this->client->request('GET', "/api/getShortUrl/$shortUrlCode");

        } catch (GuzzleException $exception) {
            $response = $exception->getResponse();
        }

        $response = json_decode(optional(optional($response)->getBody())->getContents());

        return $response;
    }

    public function createUrl($params = [])
    {

        try {
            $response = $this->client->request('POST', "/api/createShortUrl", [
                'form_params' => $params
            ]);

        } catch (GuzzleException $exception) {
            $response = $exception->getResponse();
        }

        $response = json_decode(optional(optional($response)->getBody())->getContents());

        return $response;
    }

    public function updateUrl($shortUrlCode, $params)
    {
        if (!$shortUrlCode) {
            return response('Short Url Code is Required', 403);
        }

        try {
            $response = $this->client->request('POST', "/api/changeShortUrl/$shortUrlCode", [
                'form_params' => $params
            ]);

        } catch (GuzzleException $exception) {
            $response = $exception->getResponse();
        }

        $response = json_decode(optional(optional($response)->getBody())->getContents());

        return $response;
    }

    /**
     * Delete Short Url
     *
     * @param $shortUrlCode
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteUrl($shortUrlCode)
    {
        if (!$shortUrlCode) {
            return response('Short Url Code is Required', 403);
        }

        try {
            $response = $this->client->request('DELETE', "/api/deleteShortUrl/$shortUrlCode");

        } catch (GuzzleException $exception) {
            $response = $exception->getResponse();
        }

        $response = json_decode(optional(optional($response)->getBody())->getContents());

        return $response;
    }

}
