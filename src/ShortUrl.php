<?php

/*
 * This file is part of the achais/shorturl.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\ShortUrl;

use Closure;
use Achais\ShortUrl\Contracts\StrategyInterface;
use Achais\ShortUrl\Strategies\OrderStrategy;
use Achais\ShortUrl\Support\Config;
use Achais\ShortUrl\Contracts\GatewayInterface;
use Achais\ShortUrl\Exceptions\InvalidArgumentException;
use Achais\ShortUrl\Exceptions\RuntimeException;

class ShortUrl
{
    protected $config;

    protected $defaultGateway;

    protected $customCreators = [];

    protected $gateways = [];

    protected $strategies = [];

    protected $converter;

    public function __construct(array $config)
    {
        $this->config = new Config($config);

        if (!empty($config['default'])) {
            $this->setDefaultGateway($config['default']);
        }
    }

    public function shorten($url, array $gateways = [])
    {
        $url = $this->formatUrl($url);

        if (empty($gateways)) {
            $gateways = $this->config->get('default.gateways', []);
        }

        return $this->getConverter()->shorten($url, $this->formatGateways($gateways));
    }

    public function expand($url, array $gateways = [])
    {
        $url = $this->formatUrl($url);

        if (empty($gateways)) {
            $gateways = $this->config->get('default.gateways', []);
        }

        return $this->getConverter()->expand($url, $this->formatGateways($gateways));
    }

    public function gateway($name = null)
    {
        $name = $name ?: $this->getDefaultGateway();
        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->createGateway($name);
        }

        return $this->gateways[$name];
    }

    public function extend($name, Closure $callback)
    {
        $this->customCreators[$name] = $callback;

        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getDefaultGateway()
    {
        if (empty($this->defaultGateway)) {
            throw new RuntimeException('No default gateway configured.');
        }

        return $this->defaultGateway;
    }

    public function setDefaultGateway($name)
    {
        $this->defaultGateway = $name;

        return $this;
    }

    protected function createGateway($name)
    {
        if (isset($this->customCreators[$name])) {
            $gateway = $this->callCustomCreator($name);
        } else {
            $className = $this->formatGatewayClassName($name);
            $gateway = $this->makeGateway($className, $this->config->get("gateways.{$name}", []));
        }
        if (!($gateway instanceof GatewayInterface)) {
            throw new InvalidArgumentException(\sprintf('Gateway "%s" must implement interface %s.', $name, GatewayInterface::class));
        }

        return $gateway;
    }

    protected function callCustomCreator($gateway)
    {
        return \call_user_func($this->customCreators[$gateway], $this->config->get("gateways.{$gateway}", []));
    }

    protected function formatGatewayClassName($name)
    {
        if (\class_exists($name) && \in_array(GatewayInterface::class, \class_implements($name))) {
            return $name;
        }
        $name = \ucfirst(\str_replace(['-', '_', ''], '', $name));

        return __NAMESPACE__."\\Gateways\\{$name}Gateway";
    }

    protected function makeGateway($gateway, $config)
    {
        if (!\class_exists($gateway) || !\in_array(GatewayInterface::class, \class_implements($gateway))) {
            throw new InvalidArgumentException(\sprintf('Class "%s" is a invalid shorturl gateway.', $gateway));
        }

        return new $gateway($config);
    }

    protected function getConverter()
    {
        return $this->converter ?: $this->converter = new Converter($this);
    }

    protected function formatGateways(array $gateways)
    {
        $formatted = [];
        foreach ($gateways as $gateway => $setting) {
            if (\is_int($gateway) && \is_string($setting)) {
                $gateway = $setting;
                $setting = [];
            }
            $formatted[$gateway] = $setting;
            $globalSettings = $this->config->get("gateways.{$gateway}", []);
            if (\is_string($gateway) && !empty($globalSettings) && \is_array($setting)) {
                $formatted[$gateway] = new Config(\array_merge($globalSettings, $setting));
            }
        }
        $result = [];
        foreach ($this->strategy()->apply($formatted) as $name) {
            $result[$name] = $formatted[$name];
        }

        return $result;
    }

    public function formatUrl($url)
    {
        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(\sprintf('Url "%s" is invalid.', $url));
        }

        return $url;
    }

    public function strategy($strategy = null)
    {
        if (\is_null($strategy)) {
            $strategy = $this->config->get('default.strategy', OrderStrategy::class);
        }
        if (!\class_exists($strategy)) {
            $strategy = __NAMESPACE__.'\Strategies\\'.\ucfirst($strategy);
        }
        if (!\class_exists($strategy)) {
            throw new InvalidArgumentException("Unsupported strategy \"{$strategy}\"");
        }
        if (empty($this->strategies[$strategy]) || !($this->strategies[$strategy] instanceof StrategyInterface)) {
            $this->strategies[$strategy] = new $strategy($this);
        }

        return $this->strategies[$strategy];
    }
}
