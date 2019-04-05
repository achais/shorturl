<?php
/**
 * Created by PhpStorm.
 * User: achais
 * Date: 2019-04-05
 * Time: 16:02
 */

namespace Achais\ShortUrl\Strategies;


use Achais\ShortUrl\Contracts\StrategyInterface;

class OrderStrategy implements StrategyInterface
{
    public function apply(array $gateways)
    {
        return array_keys($gateways);
    }
}
