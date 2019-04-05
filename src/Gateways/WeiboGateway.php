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

class WeiboGateway extends Gateway
{
    use HasHttpRequest;

    const SHORTEN_URL = 'http://api.weibo.com/2/short_url/shorten.json';
    const EXPAND_URL = 'http://api.weibo.com/2/short_url/expand.json';

    public function shorten($url, Config $config)
    {
        $params = [
            'source' => $config->get('source'),
            'url_long' => $url,
        ];
        $result = $this->get(self::SHORTEN_URL, $params);
        if (!isset($result['urls'])) {
            throw new GatewayErrorException();
        }
        return $result;
    }

    public function expand($url, Config $config)
    {
        $params = [
            'source' => $config->get('source'),
            'url_short' => $url,
        ];
        $result = $this->get(self::EXPAND_URL, $params);
        if (!isset($result['urls'])) {
            throw new GatewayErrorException();
        }
        return $result;
    }
}
