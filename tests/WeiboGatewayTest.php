<?php

/*
 * This file is part of the achais/shorturl.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\Shorturl\Tests;

use Achais\ShortUrl\ShortUrl;
use PHPUnit\Framework\TestCase;

class WeiboGatewayTest extends TestCase
{
    protected $config = [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认使用配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Achais\ShortUrl\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                'weibo',
            ],
        ],

        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => '/tmp/short_url.log',
            ],
            'weibo' => [
                'source' => '1771219659',
            ],
            //...
        ],
    ];

    /*
    public function testUrlShorten()
    {
        $shortUrl = new ShortUrl($this->config);

        try {
            // 长链接 -> 短链接
            // 微博做了限制有权限才可以调用
            $long_url = 'https://www.achais.com';
            $result = $shortUrl->shorten($long_url);
            //var_dump($result);
            $this->assertArrayHasKey('weibo', $result);
        } catch (Exception $exception) {
            print_r($exception->getExceptions());
        }
    }
    */

    public function testUrlExpand()
    {
        $shortUrl = new ShortUrl($this->config);

        try {
            // 短链接 -> 长链接
            $short_url = 'http://t.cn/EiReech';
            $result = $shortUrl->expand($short_url);
            //var_dump($result);
            $this->assertArrayHasKey('weibo', $result);
        } catch (Exception $exception) {
            print_r($exception->getExceptions());
        }
    }
}
