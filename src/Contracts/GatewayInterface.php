<?php

/*
 * This file is part of the achais/shorturl.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\ShortUrl\Contracts;

use Achais\ShortUrl\Support\Config;

interface GatewayInterface
{
    public function getName();

    public function shorten($url, Config $config);

    public function expand($url, Config $config);
}
