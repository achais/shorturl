<?php

/*
 * This file is part of the achais/shorturl.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\ShortUrl\Traits;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

trait HasHttpRequest
{
    /**
     * 创建一个 get 请求
     *
     * @param $endpoint
     * @param array $query
     * @param array $headers
     *
     * @return array|ResponseInterface|string
     */
    protected function get($endpoint, $query = [], $headers = [])
    {
        return $this->request('get', $endpoint, [
            'headers' => $headers,
            'query' => $query,
        ]);
    }

    /**
     * 创建一个 post 请求
     *
     * @param $endpoint
     * @param array $params
     * @param array $headers
     *
     * @return array|ResponseInterface|string
     */
    protected function post($endpoint, $params = [], $headers = [])
    {
        return $this->request('post', $endpoint, [
            'headers' => $headers,
            'form_params' => $params,
        ]);
    }

    /**
     * 创建一个基于 application/json 的 post 请求
     *
     * @param $endpoint
     * @param array $params
     * @param array $headers
     *
     * @return array|ResponseInterface|string
     */
    protected function postJson($endpoint, $params = [], $headers = [])
    {
        return $this->request('post', $endpoint, [
            'headers' => $headers,
            'json' => $params,
        ]);
    }

    /**
     * 创建一个 http 请求
     *
     * @param $method
     * @param $endpoint
     * @param array $options
     *
     * @return array|ResponseInterface|string
     */
    protected function request($method, $endpoint, $options = [])
    {
        return $this->unwrapResponse($this->getHttpClient($this->getBaseOptions())->{$method}($endpoint, $options));
    }

    /**
     * 返回基本 Guzzle 配置信息.
     *
     * @return array
     */
    protected function getBaseOptions()
    {
        $options = [
            'base_uri' => method_exists($this, 'getBaseUri') ? $this->getBaseUri() : '',
            'timeout' => method_exists($this, 'getTimeout') ? $this->getTimeout() : 5.0,
        ];

        return $options;
    }

    /**
     * 返回 http client.
     *
     * @return Client
     */
    protected function getHttpClient(array $options = [])
    {
        return new Client($options);
    }

    /**
     * 转换 response 内容为 json.
     *
     * @return ResponseInterface|array|string
     */
    protected function unwrapResponse(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $contents = $response->getBody()->getContents();
        if (false !== stripos($contentType, 'json') || stripos($contentType, 'javascript')) {
            return json_decode($contents, true);
        } elseif (false !== stripos($contentType, 'xml')) {
            return json_decode(json_encode(simplexml_load_string($contents)), true);
        }

        return $contents;
    }
}
