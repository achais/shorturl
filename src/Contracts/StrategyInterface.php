<?php
/**
 * Created by PhpStorm.
 * User: achais
 * Date: 2019-04-05
 * Time: 16:01
 */

namespace Achais\ShortUrl\Contracts;


interface StrategyInterface
{
    public function apply(array $gateways);
}
