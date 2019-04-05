<?php
/**
 * Created by PhpStorm.
 * User: achais
 * Date: 2019-04-05
 * Time: 17:09
 */

namespace Achais\ShortUrl\Gateways;


use Achais\ShortUrl\Contracts\GatewayInterface;
use Achais\ShortUrl\Support\Config;

abstract class Gateway implements GatewayInterface
{
    const DEFAULT_TIMEOUT = 5.0;

    protected $config;

    protected $timeout;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    public function getTimeout()
    {
        return $this->timeout ?: $this->config->get('timeout', self::DEFAULT_TIMEOUT);
    }

    public function setTimeout($timeout)
    {
        $this->timeout = floatval($timeout);

        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $config
     *
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    public function getName()
    {
        return \strtolower(str_replace([__NAMESPACE__ . '\\', 'Gateway'], '', \get_class($this)));
    }
}
