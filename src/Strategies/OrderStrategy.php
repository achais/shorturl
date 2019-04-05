<?php

/*
 * This file is part of the achais/shorturl.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
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
