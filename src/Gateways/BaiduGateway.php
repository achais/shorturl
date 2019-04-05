<?php

/*
 * This file is part of the achais/shorturl.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
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
        if (!isset($result['Code']) || 0 !== $result['Code']) {
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
        if (!isset($result['Code']) || 0 !== $result['Code']) {
            throw new GatewayErrorException();
        }

        return $result;
    }
}
