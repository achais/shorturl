<?php
/**
 * Created by PhpStorm.
 * User: achais
 * Date: 2019-04-05
 * Time: 15:47
 */

namespace Achais\ShortUrl\Gateways;


use Achais\ShortUrl\Exceptions\GatewayErrorException;
use Achais\ShortUrl\Support\Config;
use Achais\ShortUrl\Traits\HasHttpRequest;

class BaiduGateway extends Gateway
{
    use HasHttpRequest;

    const SHORTEN_URL = 'https://dwz.cn/admin/v2/create';
    const EXPAND_URL = 'https://dwz.cn/admin/v2/query';

    public function shorten($url, Config $config)
    {
        $headers = [
            'Token' => $config->get('token'),
        ];

        $params = [
            'url' => $url,
        ];
        $result = $this->postJson(self::SHORTEN_URL, $params, $headers);
        if (!isset($result['Code']) || $result['Code'] !== 0) {
            throw new GatewayErrorException();
        }
        return $result;
    }

    public function expand($url, Config $config)
    {
        $headers = [
            'Token' => $config->get('token'),
        ];

        $params = [
            'shortUrl' => $url,
        ];
        $result = $this->postJson(self::EXPAND_URL, $params, $headers);
        if (!isset($result['Code']) || $result['Code'] !== 0) {
            throw new GatewayErrorException();
        }
        return $result;
    }
}
