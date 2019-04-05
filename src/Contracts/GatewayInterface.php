<?php
/**
 * Created by PhpStorm.
 * User: achais
 * Date: 2019-04-05
 * Time: 15:09
 */

namespace Achais\ShortUrl\Contracts;


use Achais\ShortUrl\Support\Config;

interface GatewayInterface
{
    public function getName();

    public function shorten($url, Config $config);

    public function expand($url, Config $config);
}
