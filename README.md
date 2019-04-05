<h1 align="center"> ShortUrl </h1>

<p align="center">一键生成和还原短链接的 PHP 扩展包，我们支持多平台了哦!</p>

[![Build Status](https://travis-ci.org/achais/shorturl.svg?branch=master)](https://travis-ci.org/achais/shorturl)
![StyleCI build status](https://github.styleci.io/repos/179630448/shield) 

## 特点

1. 支持目前市面多家服务商
2. 一套写法兼容所有平台
3. 简单配置即可灵活增减服务商
4. 内置多种服务商轮询策略、支持自定义轮询策略
5. 统一的返回值格式，便于日志与监控
6. 自动轮询选择可用的服务商
7. 更多等你去发现与改进...

## 平台支持

- [百度 短链](https://dwz.cn/)
- [微博 短链](https://open.weibo.com/wiki/%E5%BE%AE%E5%8D%9AAPI#.E7.9F.AD.E9.93.BE)

## 环境要求

- PHP >= 5.6

## 安装

```shell
$ composer require achais/shorturl -vvv
```

## 使用

```php
use Achais\ShortUrl\ShortUrl;

$config = [
    // HTTP 请求的超时时间（秒）
    'timeout' => 5.0,

    // 默认使用配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Achais\ShortUrl\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'baidu',
        ],
    ],

    // 可用的网关配置
    'gateways' => [
        'baidu' => [
            'token' => '44712d2fdaaa48d0a717866e9a******',
        ],
        'weibo' => [
            'source' => '1771******',
        ],
        //...
    ],
];

$shortUrl = new ShortUrl($config);

// 长链接 -> 短链接
$long_url = 'https://www.achais.com';
$result = $shortUrl->shorten($long_url);
print_r($result);

// 短链接 -> 长链接
$short_url = 'https://dwz.cn/ZzVmHQZa';
$result = $shortUrl->expand($short_url);
var_dump($result);
```

## 各平台配置说明

### [百度 短链](https://dwz.cn/)
```php
'baidu' => [
    'token' => ''
],
```

### [微博 短链](https://open.weibo.com/wiki/%E5%BE%AE%E5%8D%9AAPI#.E7.9F.AD.E9.93.BE)
```php
'weibo' => [
    'source' => ''
],
```

## 贡献

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/achais/shorturl/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/achais/shorturl/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
